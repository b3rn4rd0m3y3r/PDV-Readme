# type MassaDeDados.csv | awk -f m004.awk | sort > fornec1.csv
BEGIN { 
	FS=";"
	}
NR > 1 { 
	printf("%s;\n", $3)
	printf("%s;\n", $4)
	printf("%s;\n", $5) 
	}
END {}