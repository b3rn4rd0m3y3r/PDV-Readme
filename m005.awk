# type fornec1.csv | awk -f m005.awk > fornec2.csv
BEGIN { 
	FS=";"
	forn_ant = ""
	}
{ 
	forn = $1
	if( forn != forn_ant ){
		printf("%s;\n", $1)
		forn_ant = forn
		}
	}
END {}