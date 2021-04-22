<?php

namespace App\Console\Commands;

use App\AMIParser;
use Illuminate\Console\Command;
use JsonException;
use Symfony\Component\Mime\MimeTypes;

class ImportAMI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ami
                            {file : The file to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import AMI Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param AMIParser $parser
     * @return int
     */
    public function handle(AMIParser $parser): int
    {
        $path = $this->argument('file');
        if (!is_readable($path)) {
            $this->error("$path does not exist, or is not readable!");
            return -1;
        }

        // NOTE: Mime guessers see JSON files as text if the size is over 2^20+1 bytes!
        $mimeType = MimeTypes::getDefault()->guessMimeType($path);
        if ($mimeType != 'application/json' && $mimeType != 'text/plain') {
            $this->error("$path is an invalid mime type (application/json or text/plain is expected)!");
            return -1;
        }
        if (($fileContent = file_get_contents($path)) === false) {
            $this->error("Error reading $path");
            return -1;
        }

        try {
            $result = $parser->parseFile($fileContent, $this->output->createProgressBar());
        } catch (JsonException) {
            $this->error("$path does not contain well-formed JSON!");
            return -1;
        }
        $summary  = "\nType: $result[type]\n";
        $summary .= "Records Parsed: $result[parsed]\n";
        $summary .= "Records Saved: $result[saved]";
        if ($result['type'] == 'Meter Usage') {
            $summary .= "\n\nOn-peak Subtotal: {$result['discrepancies']['onPeakSubTotal']}\n";
            $summary .= "Off-peak Subtotal: {$result['discrepancies']['offPeakSubTotal']}\n";
            $summary .= "Total: {$result['discrepancies']['total']}";
        }

        $this->info($summary);

        return 0;
    }
}
