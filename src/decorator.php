<?php
/**
 * Created by PhpStorm.
 * User: doctor
 * Date: 21.05.19
 * Time: 15:43
 */

interface IRemoteService
{
    /**
     * @return array
     */
    public function doSomethingAndReturnResult(): array;
}

class SomeRemoteService implements IRemoteService
{

    /**
     * @return array
     */
    public function doSomethingAndReturnResult(): array
    {
        // some logic
        return [
            "jsonrpc" => "2.0",
            "error" => [
                "code" => -32700,
                "message" => "parse_error"
            ],
            "id" => "some id",
        ];
    }
}













function printErrorMsg(array $result)
{
    if (isset($result['error'])) {
        echo '<div class="errorMsg">' . $result['error']['message'] . '</div>';
    }
}


$service = new SomeRemoteService();

$result = $service->doSomethingAndReturnResult();
printErrorMsg($result);



















class Translator
{
    protected $lang;

    protected $map = [
        'en' => [
            'parse_error' => "Parsing error"
        ],
        'ru' => [
            'parse_error' => "Ошибка при парсинге"
        ],
    ];

    /**
     * Translator constructor.
     * @param $lang
     */
    public function __construct($lang)
    {
        $this->lang = $lang;
    }


    /**
     * @param string $key
     * @return string
     */
    public function translate(string $key): string
    {
        return $this->map[$this->lang][$key] ?? $key;
    }
}

class SomeRemoteServiceDecorator implements IRemoteService
{
    /**
     * @var IRemoteService
     */
    protected $remoteService;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * SomeRemoteServiceDecorator constructor.
     * @param IRemoteService $remoteService
     * @param Translator $translator
     */
    public function __construct(IRemoteService $remoteService, Translator $translator)
    {
        $this->remoteService = $remoteService;
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function doSomethingAndReturnResult(): array
    {
        $res = $this->remoteService->doSomethingAndReturnResult();
        if (isset($res['error'])) {
            $res['error']['message'] = $this->translator->translate($res['error']['message']);
        }

        return $res;
    }
}



$translator = new Translator('ru');
$service = new SomeRemoteServiceDecorator($service, $translator);

$result = $service->doSomethingAndReturnResult();
printErrorMsg($result);
