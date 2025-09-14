<?php
// 代码生成时间: 2025-09-14 13:16:17
 * It consolidates files based on their extensions into subfolders named after the extensions.
 */
class FolderOrganizer
{
    private $sourceDir;
    private $extensions = [];

    /**
     * Constructor
     *
     * @param string $sourceDir The directory to organize
     */
    public function __construct($sourceDir)
    {
        if (!is_dir($sourceDir)) {
            throw new Exception("The specified directory does not exist.");
        }

        $this->sourceDir = $sourceDir;
    }

    /**
     * Organize files in the specified directory
     */
    public function organize()
    {
        $files = $this->getFilesInDirectory($this->sourceDir);
        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($extension, $this->extensions)) {
                $this->extensions[] = $extension;
                $this->createFolder($extension);
            }
            $this->moveFile($file, $extension);
        }
    }

    /**
     * Get all files in the directory
     *
     * @param string $dir The directory path
     * @return array An array of file paths
     */
    private function getFilesInDirectory($dir)
    {
        $files = [];
        $iterator = new RecursiveDirectoryIterator($dir);
        foreach (new RecursiveIteratorIterator($iterator) as $file) {
            if ($file->isFile()) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Create a folder for a given extension
     *
     * @param string $extension The file extension
     */
    private function createFolder($extension)
    {
        $folderPath = $this->sourceDir . DIRECTORY_SEPARATOR . $extension;
        if (!is_dir($folderPath)) {
            mkdir($folderPath);
        }
    }

    /**
     * Move a file to a folder based on its extension
     *
     * @param string $file The file path
     * @param string $extension The file extension
     */
    private function moveFile($file, $extension)
    {
        $destination = $this->sourceDir . DIRECTORY_SEPARATOR . $extension . DIRECTORY_SEPARATOR . basename($file);
        if (!rename($file, $destination)) {
            throw new Exception("Failed to move file: {$file}");
        }
    }
}

// Example usage:
try {
    $organizer = new FolderOrganizer('/path/to/your/directory');
    $organizer->organize();
    echo "Files organized successfully.
";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "
";
}
