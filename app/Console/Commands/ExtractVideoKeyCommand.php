<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Video;
use Illuminate\Console\Command;

class ExtractVideoKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:extract-video-key-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('updating Video...');
        $videos = Video::on('mysql')->get();
        if(!$videos) return;
        foreach ($videos as $row){
            $videoKey=Helper::getYouTubeId($row->video);
            if(!$videoKey) continue;

            Video::on('mysql')
                ->where('idvideo',$row->idvideo)
                ->update([
                    'video'=>$videoKey,

                ]);
            $this->info('updating Video...'. $videoKey);
            $videoKey=null;
        }
        $this->info('Update done successfully');
    }
}
