<?php
namespace Lum\Yar\Packet;

use Lum\Server\Encoder;
use Lum\Server\Encoder\EncoderFactory;
use Lum\Server\Exception\EncodeException;
use Lum\Yar\Exception\YarServerException;

/**
 * Class Yar
 *
 * @package Lum\Yar\Packet
 */
final class Yar
{
    const HEADER_SIZE = 82;
    const PACKAGER_SIZE = 8;
    const MAGIC_NUM = 0x80DFEC60;
    const PACKAGER_JSON = 'JSON';
    const PACKAGER_MSG = 'MSGPACK';
    const PACKAGER_PHP = 'PHP';
    //packagers and encoder map
    private const PACKAGERS = [
        self::PACKAGER_JSON => 'JSON',
        self::PACKAGER_MSG => 'MsgPack',
        self::PACKAGER_PHP => 'PHP',
    ];
    const REQUEST_HEADER_FORMAT = 'Nid/nver/NmagicNum/Nreserved/A32provider/A32token/Nlength';
    const RESPONSE_HEADER_FORMAT = 'NnNNa32a32N';

    /**
     * @param string $packager
     *
     * @return Encoder
     * @throws EncodeException
     */
    public static function getEncoderByPackager(string $packager) : Encoder
    {
        if (!isset(static::PACKAGERS[$packager])) {
            throw new EncodeException(sprintf('unsupported packager [%s]', $packager));
        }

        return EncoderFactory::createEncoder(static::PACKAGERS[$packager]);
    }

    /**
     * @param string $str
     *
     * @return string
     * @throws YarServerException
     */
    public static function getPackager(string $str) : string
    {
        foreach (Yar::PACKAGERS as $packager => $encoder) {
            if (0 == strncasecmp($packager, $str, strlen($packager))) {
                return $packager;
            }
        }
        throw new YarServerException(
            sprintf('invalid packager [%s]', $str), 1
        );
    }
}