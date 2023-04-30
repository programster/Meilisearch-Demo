<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DocumentController extends AbstractSlimController
{
    public static function registerRoutes($app)
    {
        $app->get('/', function (Psr\Http\Message\ServerRequestInterface $request, Psr\Http\Message\ResponseInterface $response, $args) {
            $controller = new DocumentController($request, $response, $args);
            return $controller->index();
        });

        # Handle the user posting a document
        $app->post('/documents', function (Psr\Http\Message\ServerRequestInterface $request, Psr\Http\Message\ResponseInterface $response, $args) {
            $controller = new DocumentController($request, $response, $args);
            return $controller->handleDocumentUpload();
        });

        # Handle the user performing a search.
        $app->get('/documents/search', function (Psr\Http\Message\ServerRequestInterface $request, Psr\Http\Message\ResponseInterface $response, $args) {
            $controller = new DocumentController($request, $response, $args);
            return $controller->handleSearchRequest();
        });
    }


    private function index() : Psr\Http\Message\ResponseInterface
    {
        $content = new ViewUploadForm();
        $htmlShell = new ViewHtmlShell($content);
        $this->m_response->getBody()->write((string)$htmlShell);
        return $this->m_response;
    }


    private function handleDocumentUpload() : Psr\Http\Message\ResponseInterface
    {
        $uploadManager = new Programster\UploadFileManager\UploadFileManager();
        $uploads = $uploadManager->getUploadFiles();

        foreach ($uploads as $upload)
        {
            /* @var $upload \Programster\UploadFileManager\UploadFile */
            if ($upload->wasSuccessful())
            {
                if (DocumentTable::getInstance()->doesFileExist($upload->getFilepath()) === false)
                {
                    $newDocumentUuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
                    $newDestination = "/storage/{$newDocumentUuid}";
                    $mimetype = $upload->getMimeType();
                    $originalFilename = $upload->getName();
                    $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
                    move_uploaded_file($upload->getFilepath(), $newDestination);
                    $name = $upload->getName();
                    Document::createNew($originalFilename, $newDestination, $newDocumentUuid);
                    $document = new OfficeDocument($newDestination, $extension);
                    $client = SiteSpecific::getMeiliClient();
                    $index = $client->getIndex(DOCUMENT_INDEX);    // If you already created your index

                    $documents = [
                        ['id' => $newDocumentUuid, 'content' => (string)$document],
                    ];

                    $index->addDocuments($documents);
                    $content = "<p>Document added.</p>";
                }
                else
                {
                    $content = "<p>Document already exists in our database.</p>";
                }
            }
        }

        $htmlShell = new ViewHtmlShell($content);
        $this->m_response->getBody()->write((string)$htmlShell);
        return $this->m_response;
    }


    private function handleSearchRequest() : Psr\Http\Message\ResponseInterface
    {
        $allGetVars = $this->m_request->getQueryParams();
        $search = $allGetVars['search'];

        $client = SiteSpecific::getMeiliClient();
        $index = $client->getIndex(DOCUMENT_INDEX);

        $responseData = $index->search($search);
        $bodyJson = json_encode($responseData->getHits(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $body = $this->m_response->getBody();
        $body->write($bodyJson);

        $newResponse = $this->m_response->withStatus(200)->withHeader("Content-Type", "application/json")->withBody($body);
        return $newResponse;
    }
}
