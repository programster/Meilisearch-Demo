Meilisearch Demo
================

A demo example of using [MeiliSearch](https://github.com/meilisearch/MeiliSearch) in PHP for 
searching uploaded documents.

When deployed, this will show you a form for uploading documents and a form for searching. 
When you upload documents, the site makes sure to take the hash of the document to prevent uploading 
the exact same document twice. The tool will try and convert the document to a string format that 
can be searched, and gets passed to Meilisearch. 

When you enter a search into the search form, it performs a Meilisearch and returns the JSON response.


## Build And Run
1. Create your own `.env` file from the example.
1. Navigate to the `app/` folder.
1. Run `composer install`
1. Navigate back to the top level directory.
1. Run `docker-compose build`
1. Run `docker-compose up`

Now got to `http://localhost` in your browser and use the form to upload any number of Word 
documents. You can then use the search to retrieve the IDs of documents that have the searched 
content. It's not pretty but it demonstrates that the backend functionality works.



## Roadmap
* [Add rankings to indexes](https://docs.meilisearch.com/references/ranking_rules.html#get-ranking-rules)
* Add drag and drop for mass uploading lots of files.
* Add metadata fields to upload form (title, description, tags).
* Ability to edit uploaded document metadata.
* Pretty view of search results with ability to download the documents.
