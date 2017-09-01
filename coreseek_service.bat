@title coreseek service
::cd to coreseek path
cd %~dp0vendor\coreseek-4.1-win32
::using docs's config
bin\indexer -c etc\csft_mysql.conf --all
bin\searchd -c etc\csft_mysql.conf --console
pause