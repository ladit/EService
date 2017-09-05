@title coreseek service
::cd to coreseek path
cd %~dp0vendor\coreseek-4.1-win32
::using docs's config
bin\indexer -c %~dp0docs\csft_mysql.conf --all
bin\searchd -c %~dp0docs\csft_mysql.conf --console
pause