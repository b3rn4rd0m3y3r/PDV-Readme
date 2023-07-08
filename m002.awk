function random(a, b){
	c = b - a
	c = a+c*rand()
	return c
	}
BEGIN { 
	FS=";"
	}
NR > 1 { 
	printf("%s|%s|%s|%5.2f\n", $1,$2,$3,random($6,$7))
	printf("%s|%s|%s|%5.2f\n", $1,$2,$4,random($6,$7))
	printf("%s|%s|%s|%5.2f\n", $1,$2,$5,random($6,$7)) 
	}
END {}