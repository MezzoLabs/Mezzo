<?php


namespace MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Controllers;


use Illuminate\Http\Request;
use MezzoLabs\Mezzo\Http\Controllers\CockpitController;
use MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Pages\DebugPage;
use MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Pages\LogsPage;
use Symfony\Component\Finder\SplFileInfo;

class DebugController extends CockpitController
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    public $fileSystem;

    public function __construct()
    {
        parent::__construct();
        $this->fileSystem = app(\Illuminate\Filesystem\Filesystem::class);
    }

    public function show()
    {
        return $this->page(DebugPage::class, []);
    }

    public function logs(Request $request)
    {
        $logs = collect($this->fileSystem->allFiles(storage_path('logs')));

        $logs = $logs->sortByDesc(function(SplFileInfo $log){
            return $log->getRealPath();
        });

        $activeLog = null;

        if ($request->has('log')) {
            $activeLogFile = $logs->get($logs->search(function (SplFileInfo $log) use ($request, &$activeLog) {
                return $log->getRealPath() == $request->get('log');
            }));

            $activeLog = [
                'file' => $activeLogFile,
                'content' => trim(file_get_contents($activeLogFile->getRealPath()))
            ];

            $activeLog['lines'] = preg_split("/((\r?\n)|(\r\n?))/", $activeLog['content']);

            $activeLog['parts'] = [""];

            foreach ($activeLog['lines'] as $line) {
                if(preg_match('|\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]\s|', $line)){
                    $activeLog['parts'][] = "";
                }

                $activeLog['parts'][count($activeLog['parts']) - 1] .= trim($line)  . "\r\n";
            }

            $activeLog['parts'] = array_filter($activeLog['parts']);


        }

        return $this->page(LogsPage::class, [
            'all_logs' => $logs,
            'active_log' => $activeLog
        ]);
    }
}