@title coreseek service
@cd ..\vendor\coreseek-4.1-win32
@bin\indexer -c ..\..\bin\search_question.conf --all
@bin\searchd -c ..\..\bin\search_question.conf --console
pause