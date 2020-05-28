<?php
namespace Lum\Yar;

use ErrorException;
use Lum\Server\Protocol\RequestPacket;
use Lum\Server\Protocol\Writer;
use Lum\Yar\Exception\YarServerException;
use Lum\Yar\Packet\Yar;
use Lum\Yar\Packet\YarReader;
use Lum\Yar\Packet\YarRequestBody;
use Lum\Yar\Packet\YarWriter;

/**
 * Class YarHandler
 *
 * @package Lum\Yar
 */
final class YarHandler
{
    private $serviceMap;

    /**
     * YarHandler constructor.
     *
     * @param array $serviceMap
     */
    public function __construct($serviceMap)
    {
        $this->serviceMap = $serviceMap;
    }

    /**
     * parse yar client request
     *
     * @param string $data
     * @param string $serviceAlias
     *
     * @return null|Writer
     * @throws YarServerException
     * @throws \Lum\Server\Exception\EncodeException
     */
    public function handle(string $data, string $serviceAlias = '') : ?Writer
    {
        $status = 0;
        $ret = '';
        $packager = null;
        try {
            $packet = $this->read($data);
            $body = $packet ? $packet->getRequestBody() : null;
            if ($body instanceof YarRequestBody) {
                $packager = $body->getPackager();
                $request = $body->getRequest();
                $params = $request->getParams();
                $method = $request->getMethod();
                $service = null;
                if ($serviceAlias && is_array($this->serviceMap) &&
                    isset($this->serviceMap[$serviceAlias])) {
                    $serviceName = $this->serviceMap[$serviceAlias];
                    $service = new $serviceName;
                } elseif (is_string($this->serviceMap)) {
                    $service = new $this->serviceMap;
                }
                if ($service && method_exists($service, $method)) {
                    $ret = $service->$method(...$params);
                } else {
                    $ret = sprintf("unsupported method '%s'", $method);
                    $status = 1;
                }
            }
        } catch (YarServerException $e) {
            $ret = $e->getMessage();
            $status = $e->getCode();
        } catch (ErrorException $err) {
            //TODO???
            $ret = 'error:'.$err->getMessage();
            $status = 1;
        }
        $result = new YarResult($status, $ret);
        if ($packager) {
            $res = new YarResponse(Yar::getPackager($packager), $result);
        } else {
            $res = new YarResponse(Yar::PACKAGER_MSG, $result);
        }
        $writer = (new YarWriter);
        $writer->write($res);
        var_dump($writer);

        return $writer;
    }

    /**
     * @param string $data
     *
     * @return RequestPacket|null
     * @throws YarServerException
     * @throws \Lum\Server\Exception\EncodeException
     */
    private function read(string $data) : ?RequestPacket
    {
        return (new YarReader($data))->read();
    }
}