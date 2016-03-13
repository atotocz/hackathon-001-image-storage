<?php
namespace Hackaton\ImageStorage;

class ServiceNotFoundException extends \Exception {
}

class ServiceMustBeObjectException extends \Exception {
}

class ControllerMustReturnsResponseException extends \Exception {
}

class DirectoryNotFoundException extends \Exception {
}

class CantCreateDirectoryException extends \Exception {
}

class CommandNotFoundException extends \Exception {
}

class ProfileNotFoundException extends \Exception {
}

class FileNotFoundException extends \Exception {
}

class FileCouldNotBeDeletedException extends \Exception {
}

class HttpException extends \Exception {
  protected $code = 400;
}

class BadContentTypeException extends HttpException {
}

class HttpMethodNotFoundException extends HttpException {
  protected $code = 405;
}

class ControllerNotFoundException extends HttpException {
  protected $code = 405;
}

class ActionNotFoundException extends ControllerNotFoundException {
}

class CantLoadContentException extends \Exception {
}

class NoContentSourceFoundException extends CantLoadContentException {
}
