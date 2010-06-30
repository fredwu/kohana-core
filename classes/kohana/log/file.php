<?php defined('SYSPATH') or die('No direct script access.');
/**
 * File log writer. Writes out messages and stores them in a YYYY/MM directory.
 *
 * @package    Kohana
 * @category   Logging
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_Log_File extends Kohana_Log_Writer {

	// Directory to place log files in
	protected $_directory;

	/**
	 * Creates a new file logger. Checks that the directory exists and
	 * is writable.
	 *
	 *     $writer = new Kohana_Log_File($directory);
	 *
	 * @param   string  log directory
	 * @return  void
	 */
	public function __construct($directory)
	{
		if ( ! is_dir($directory) OR ! is_writable($directory))
		{
			throw new Kohana_Exception('Directory :dir must be writable',
				array(':dir' => Kohana::debug_path($directory)));
		}

		// Determine the directory path
		$this->_directory = realpath($directory).DIRECTORY_SEPARATOR;
	}

	/**
	 * Writes each of the messages into the log file. By default the log file
	 * will be appended to the `YYYY/MM/DD.log.php` file, where YYYY is the
	 * current year, MM is the current month, and DD is the current day.
	 *
	 *     $writer->write($messages);
	 *
	 * @param   array    messages
	 * @param   mixed    prepend          set a string to prepend it to the log file,
	 *                                    `TRUE` to prepend the default Kohana security line,
	 *                                    `FALSE` to disable it
	 * @param   boolean  sub_directories  `FALSE` to disable the `YYYY/MM/` directory structure
	 * @param   mixed    custom_filename  set a string to use it as the filename,
	 *                                    `FALSE` to use the current day as the filename
	 * @return  void
	 */
	public function write(array $messages, $prepend = TRUE, $sub_directories = TRUE, $custom_filename = FALSE)
	{
		$directory = $this->which_directory($sub_directories);
		
		// Set the name of the log file
		$filename = is_string($custom_filename) ? $custom_filename : date('d').EXT;
		$filename = $directory.$filename;

		if ( ! file_exists($filename))
		{
			// Create the log file
			file_put_contents($filename, $this->prepend_content($prepend));

			// Allow anyone to write to log files
			chmod($filename, 0666);
		}

		// Set the log line format
		$format = 'time --- type: body';

		foreach ($messages as $message)
		{
			// Write each message into the log file
			file_put_contents($filename, PHP_EOL.strtr($format, $message), FILE_APPEND);
		}
	}
	
	/**
	 * Determines the log file directory structure
	 *
	 * @param   boolean  sub_directories  `FALSE` to disable the `YYYY/MM/` directory structure
	 * @return  string                    the directory
	 */
	private function which_directory($sub_directories = TRUE)
	{
		if ($sub_directories === TRUE)
		{
			// Set the yearly directory name
			$directory = $this->_directory.date('Y').DIRECTORY_SEPARATOR;

			if ( ! is_dir($directory))
			{
				// Create the yearly directory
				mkdir($directory, 0777);

				// Set permissions (must be manually set to fix umask issues)
				chmod($directory, 0777);
			}

			// Add the month to the directory
			$directory .= date('m').DIRECTORY_SEPARATOR;

			if ( ! is_dir($directory))
			{
				// Create the yearly directory
				mkdir($directory, 0777);

				// Set permissions (must be manually set to fix umask issues)
				chmod($directory, 0777);
			}
		}
		else
		{
			$directory = $this->_directory;
		}
		
		return $directory;
	}
	
	/**
	 * Content to prepend to the log file
	 *
	 * @param   mixed    prepend          set a string to prepend it to the log file,
	 *                                    `TRUE` to prepend the default Kohana security line,
	 *                                    `FALSE` to disable it
	 * @return  string                    the content to be prepended
	 */
	private function prepend_content($prepend = TRUE)
	{
		if ($prepend === TRUE)
		{
			$prepend_content = Kohana::FILE_SECURITY.' ?>'.PHP_EOL;
		}
		elseif (is_string($prepend))
		{
			$prepend_content = $prepend.PHP_EOL;
		}
		else
		{
			$prepend_content = NULL;
		}
		
		return $prepend_content;
	}

} // End Kohana_Log_File