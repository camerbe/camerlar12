/*
 * Lecteur audio de l'article (SpeechSynthesis)
 * Chargé en defer : le DOM est déjà parsé à l'exécution.
 * Le texte n'est extrait qu'au premier clic sur "Écouter".
 */
document.addEventListener('DOMContentLoaded', function () {

    if (!('speechSynthesis' in window)) {
        var reader = document.getElementById('articleReader');
        if (reader) reader.style.display = 'none';
        return;
    }

    var playButton   = document.getElementById('readerPlay');
    var playText     = document.getElementById('readerPlayText');
    var playIcon     = document.getElementById('readerPlayIcon');
    var pauseButton  = document.getElementById('readerPause');
    var stopButton   = document.getElementById('readerStop');
    var rateSelect   = document.getElementById('readerRate');
    var voiceSelect  = document.getElementById('readerVoice');
    var status       = document.getElementById('readerStatus');
    var progressText = document.getElementById('readerProgress');
    var progressBar  = document.getElementById('readerProgressBar');
    var articleBody  = document.querySelector('[itemprop="articleBody"]');

    if (!playButton || !articleBody) return;

    var utterance = null;
    var isReading = false;
    var isPaused = false;
    var currentPosition = 0;
    var articleText = null;
    var chunks = null;

    function getArticleText() {
        var clone = articleBody.cloneNode(true);
        clone.querySelectorAll('script, style, iframe, video, audio, img')
            .forEach(function (el) { el.remove(); });
        return clone.innerText.replace(/\s+/g, ' ').trim();
    }

    function splitText(text, maxLength) {
        maxLength = maxLength || 220;
        var sentences = text.match(/[^.!?]+[.!?]+|[^.!?]+$/g) || [];
        var result = [];
        var current = '';

        sentences.forEach(function (sentence) {
            sentence = sentence.trim();
            if (current.length + sentence.length + 1 > maxLength) {
                if (current) result.push(current);
                current = sentence;
            } else {
                current += ' ' + sentence;
            }
        });

        if (current) result.push(current);
        return result;
    }

    function prepareText() {
        if (chunks === null) {
            articleText = getArticleText();
            chunks = articleText ? splitText(articleText) : [];
        }
    }

    function loadVoices() {
        var voices = speechSynthesis.getVoices();
        voiceSelect.innerHTML = '<option value="">Voix française</option>';

        voices.filter(function (v) {
            return v.lang && v.lang.toLowerCase().startsWith('fr');
        }).forEach(function (voice) {
            var option = document.createElement('option');
            option.value = voice.name;
            option.textContent = voice.name + ' (' + voice.lang + ')';
            voiceSelect.appendChild(option);
        });
    }

    loadVoices();
    speechSynthesis.onvoiceschanged = loadVoices;

    function getSelectedVoice() {
        return speechSynthesis.getVoices().find(function (v) {
            return v.name === voiceSelect.value;
        });
    }

    function updateProgress() {
        if (!chunks || !chunks.length) return;
        var percent = Math.round((currentPosition / chunks.length) * 100);
        progressText.textContent = percent + '%';
        progressBar.style.width = percent + '%';
    }

    function speakChunk() {
        if (currentPosition >= chunks.length) { finishReading(); return; }

        utterance = new SpeechSynthesisUtterance(chunks[currentPosition]);
        utterance.lang = 'fr-FR';
        utterance.rate = parseFloat(rateSelect.value);
        utterance.pitch = 1;
        utterance.volume = 1;

        var voice = getSelectedVoice();
        if (voice) { utterance.voice = voice; utterance.lang = voice.lang; }

        utterance.onstart = function () {
            isReading = true;
            isPaused = false;
            updateInterface();
            status.textContent = 'Lecture de l\'article…';
        };

        utterance.onend = function () {
            if (!isReading) return;
            currentPosition++;
            updateProgress();
            speakChunk();
        };

        utterance.onerror = function (event) {
            console.error('Erreur SpeechSynthesis:', event);
            finishReading();
        };

        speechSynthesis.speak(utterance);
    }

    function startReading() {
        prepareText();
        if (!chunks.length) return;

        if (currentPosition >= chunks.length) currentPosition = 0;

        speechSynthesis.cancel();
        isReading = true;
        isPaused = false;
        updateInterface();
        speakChunk();
    }

    function pauseReading() {
        if (!isReading) return;
        speechSynthesis.pause();
        isPaused = true;
        status.textContent = 'Lecture en pause';
        updateInterface();
    }

    function resumeReading() {
        if (!isReading) return;
        speechSynthesis.resume();
        isPaused = false;
        status.textContent = 'Lecture en cours…';
        updateInterface();
    }

    function stopReading() {
        speechSynthesis.cancel();
        isReading = false;
        isPaused = false;
        currentPosition = 0;
        updateProgress();
        status.textContent = 'Écoutez cet article';
        updateInterface();
    }

    function finishReading() {
        speechSynthesis.cancel();
        isReading = false;
        isPaused = false;
        currentPosition = 0;
        progressText.textContent = '100%';
        progressBar.style.width = '100%';
        status.textContent = 'Lecture terminée';
        updateInterface();
    }

    function updateInterface() {
        if (isReading) {
            pauseButton.classList.remove('hidden');
            pauseButton.classList.add('flex');
            stopButton.classList.remove('hidden');
            stopButton.classList.add('flex');

            if (isPaused) {
                playText.textContent = 'Reprendre';
                playIcon.innerHTML = '<path d="M8 5v14l11-7z"/>';
            } else {
                playText.textContent = 'Lecture en cours';
                playIcon.innerHTML = '<path d="M6 4h4v16H6zM14 4h4v16h-4z"/>';
            }
        } else {
            pauseButton.classList.add('hidden');
            pauseButton.classList.remove('flex');
            stopButton.classList.add('hidden');
            stopButton.classList.remove('flex');
            playText.textContent = "Écouter l'article";
            playIcon.innerHTML = '<path d="M8 5v14l11-7z"/>';
        }
    }

    playButton.addEventListener('click', function () {
        if (!isReading) startReading();
        else if (isPaused) resumeReading();
        else pauseReading();
    });

    pauseButton.addEventListener('click', pauseReading);
    stopButton.addEventListener('click', stopReading);

    rateSelect.addEventListener('change', function () {
        if (!isReading) return;
        speechSynthesis.cancel();
        speakChunk();
    });

    voiceSelect.addEventListener('change', function () {
        if (!isReading) return;
        speechSynthesis.cancel();
        speakChunk();
    });

    window.addEventListener('beforeunload', function () {
        speechSynthesis.cancel();
    });
});
