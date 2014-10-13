REM Don't show messages
echo off
REM Clear the screen
cls

REM open the 'RSGallery2_documentation' with XMLmind XML Editor
REM start C:\Progra~1\XMLmind_XML_Editor\bin\xxe.exe
REM start C:\Progra~1\XMLmind_XML_Editor\bin\xxe.exe "C:\Program Files\Dobudish\documents\RSGallery2_documentation\input\RSGallery2_documentation.xml"

REM pause 
REM echo Wait for Dobudish to start... 
REM echo ...(then edit, when done click any key to output and show the pdf)
REM echo 
REM pause

REM project called 'RSGallery2_documentation', converting it to PDF
REM call the batchfile used to convert
call generator.bat RSGallery2_documentation pdf

echo Check for errors before opening PDF.
REM pause 

REM open the resulting PDF
start Acrobat.exe "C:\Program Files\dobudish\documents\RSGallery2_documentation\output\pdf\RSGallery2_documentation.pdf"