<?php
/**
 * Created by PhpStorm.
 * User: doctor
 * Date: 21.05.19
 * Time: 11:22
 */

interface IErrorReport
{

    /**
     * @return string
     */
    function getFilePath(): string;

    /**
     * @return int
     */
    function getLine(): int;

    /**
     * @return string
     */
    function getMessage(): string;
}


class SimpleErrorReport implements IErrorReport
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var int
     */
    private $line;

    /**
     * @var string
     */
    private $message;

    /**
     * SimpleBook constructor.
     * @param string $filePath
     * @param int $line
     * @param string $message
     */
    function __construct(string $filePath, int $line, string $message)
    {
        $this->filePath = $filePath;
        $this->line = $line;
        $this->message = $message;
    }

    /**
     * @return string
     */
    function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    function getLine(): int
    {
        return $this->line;
    }
}


class StackError
{
    /**
     * @var array
     */
    private $collection = [];

    /**
     * @param IErrorReport $error
     * @return StackError
     */
    public function addToCollection(IErrorReport $error): StackError
    {
        $hash = md5(md5($error->getFilePath()) . md5($error->getLine()) . md5($error->getMessage()));
        $this->collection[$hash] = $this->collection[$hash] ?? $error;
        return $this;
    }

    //////////
}


$stackError = new StackError();

$stackError
    ->addToCollection(new SimpleErrorReport("/path/to/file/1", 100,  "Some error"))
    ->addToCollection(new SimpleErrorReport("/path/to/file/2", 100,  "Some error"))
    ->addToCollection(new SimpleErrorReport("/path/to/file/2", 120,  "Some error 2"));


class ErrorFromLog
{
    /**
     * @var string
     */
    public $name;

    /**
     * BookFromCsv constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }


}

$logError1 = new ErrorFromLog("/path/to/file/1:200 Some error 3");
$logError2 = new ErrorFromLog("/path/to/file/3|100|Some error 2");


class ErrorReportFromLogAdapter implements IErrorReport
{
    /**
     * @var IErrorReport
     */
    private $error;

    /**
     * BookAdapter constructor.
     * @param ErrorFromLog $error
     */
    function __construct(ErrorFromLog $error)
    {
        $data = explode(':', $error->name);
        $path = trim($data[0]);
        $data2 = explode(' ', $data[1]);
        $line = (int)trim($data2[0]);
        $msg = implode(" ", trim($data2[1]));

        $this->error = new SimpleErrorReport($path, $line, $msg);
    }

    /**
     * @return string
     */
    function getFilePath(): string
    {
        return $this->error->getFilePath();
    }

    /**
     * @return int
     */
    function getLine(): int
    {
        return $this->error->getLine();
    }

    /**
     * @return string
     */
    function getMessage(): string
    {
        return $this->error->getMessage();
    }
}


$stackError
    ->addToCollection(new ErrorReportFromLogAdapter($logError1))
    ->addToCollection(new ErrorReportFromLogAdapter($logError2));

