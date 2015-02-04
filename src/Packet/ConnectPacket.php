<?php

namespace Att\M2X\MQTT\Packet;

class ConnectPacket extends Packet {

/**
 * The Client Identifier (Client ID) is between 1 and 23 characters long,
 * and uniquely identifies the client to the server.
 *
 * @var string
 */
  protected $clientId = null;

/**
 * Set to false to keep a persistent session with the server
 *
 * @var boolean
 */
  protected $cleanSession = true;

/**
 * Period the server should keep connection open for between pings (in seconds)
 *
 * @var integer
 */
  protected $keepAlive = 15;

/**
 * The username for authenticating with the server
 *
 * @var string
 */
  protected $username = null;

/**
 * The password for authenticating with the server
 *
 * @var string
 */
  protected $password = null;

  public function __construct($options = array()) {
    foreach ($options as $name => $value) {
      if (property_exists($this, $name)) {
        $this->{$name} = $value;
      }
    }

    parent::__construct(Packet::TYPE_CONNECT);
  }

  public function encodeBody() {
    $this->encodeString(self::PROTOCOL_NAME);
    $this->buffer[] = self::PROTOCOL_VERSION;

    //Flags
    $flags = 0;

    if ($this->username !== null) {
      $flags = $flags || 0x80;
    }

    if ($this->password !== null) {
      $flags = $flags || 0x40;
    }

    if ($this->cleanSession) {
      $flags = $flags || 0x02;
    }

    $this->buffer[] = $flags;

    //Keep Alive
    $this->buffer[] = 0x00;
    $this->buffer[] = $this->keepAlive;

    //Payload
    $this->encodeString($this->clientId);

    if ($this->username !== null) {
      $this->encodeString($this->username);
    }

    if ($this->password !== null) {
      $this->encodeString($this->password);
    }
  }
}
