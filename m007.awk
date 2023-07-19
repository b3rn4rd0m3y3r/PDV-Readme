function random(a, b){
	c = b - a
	c = a+c*rand()
	return c
	}
BEGIN { 
	FS=";"
	i = 1
	}
{ 
	arr[i,1] = $1
	arr[i,2] = $2
	arr[i,3] = $3
	arr[i,4] = $4
	i++
	}
END {
	for(j=1;j<i;j++){
		printf("%d|%s|%s|%s|%s\n",j,arr[j,1],arr[j,2],arr[j,3],arr[j,4])
		}
	}