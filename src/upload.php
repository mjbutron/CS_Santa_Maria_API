<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Upload files

// POST: Upload file
$app->post('/admin/api/upload', function(Request $request, Response $response, array $args){

  $uploadedFiles = $request->getUploadedFiles();
  $uploadedFile = $uploadedFiles['image'];
  $path = $request->getParam('path');

  if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
      $filename = $uploadedFile->getClientFilename();
      $type = $uploadedFile->getClientMediaType();
      $name = uniqid(date('dmY').'-');
      $name .= '-'.$uploadedFile->getClientFilename();
      $uploadedFile->moveTo($path . DIRECTORY_SEPARATOR . $name);
      return $this->response->withJson(['cod' => '200', 'message' => $name]);
  }

  return $this->response->withJson(['cod' => '500', 'message' => 'No se ha podido cargar la imagen']);

});
