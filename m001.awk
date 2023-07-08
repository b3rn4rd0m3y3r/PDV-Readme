BEGIN { FS=";"}
{ 
	printf("%s|%s|%s|%s|%s\n", $1,$2,$3,$6,$7)
	printf("%s|%s|%s|%s|%s\n", $1,$2,$4,$6,$7)
	printf("%s|%s|%s|%s|%s\n", $1,$2,$5,$6,$7) 
	}
END {}