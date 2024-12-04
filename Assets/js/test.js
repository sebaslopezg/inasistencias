const input = document.getElementById('excel')



input.addEventListener('change', () => {

    if (input.files.length > 0) {
        let fileName = input.files[0].name;
        let fileExtention = fileName.split('.')
        fileExtention = fileExtention[fileExtention.length-1]

        if (fileExtention != 'xlsx') {
            console.log('formato no valido')
        }else{
            readXlsxFile(input.files[0]).then((rows) => {
                console.log(rows)
            })
        }
    }
})