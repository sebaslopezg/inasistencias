const input = document.getElementById('excel')
const alertZone = document.querySelector('#alertZone')
//const btnGuardarHorario = document.querySelector('#btnGuardarHorario')
const display = document.querySelector('#display')
const displayModal = document.querySelector('#displayModal')

const date = new Date()
const meses = [
    'ENERO', 
    'FEBRERO', 
    'MARZO', 
    'ABRIL', 
    'MAYO', 
    'JUNIO', 
    'JULIO', 
    'AGOSTO', 
    'SEPTIEMBRE', 
    'OCTUBRE', 
    'NOVIEMBRE', 
    'DICIEMBRE'
]

const diasSemana = [
    'LUNES',
    'MARTES',
    'MIÉRCOLES',
    'JUEVES',
    'VIERNES',
    'SÁBADO'
]
let horario = {}

//array solo para testear
const celdas = [
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z']

let fileStatus = {}
let ficha = null
let cantidadCeldasProcesadas
let cantidadDatosFormateados
let dataProcesada

document.addEventListener('click', (e)=>{
    try{
        if (e.target.closest('button').getAttribute('id') == 'btnGuardarHorario') {
            if (horario != null) {
                sentData()
            }  
        }

        if (e.target.closest('button').getAttribute('data-action') == 'datosValidos') {
            fntPrintHorario()
            $('#horarioModal').modal('show')
        }

        if (e.target.closest('button').getAttribute('data-action') == 'datosOrganizados') {
            printProcesData()
            $('#horarioModal').modal('show')
        }
    }catch{}

}) 

input.addEventListener('change', () => {
    fileStatus = {
        code: 0,
        msg: ""
    }

    if (input.files.length > 0) {
        let fileName = input.files[0].name;
        let fileExtention = fileName.split('.')
        fileExtention = fileExtention[fileExtention.length-1]

        if (fileExtention != 'xlsx') {
            fileStatus = {
                code: 1,
                msg: "El archivo no tiene el formato incorrecto, solo se permiten archivos .xlsx"
            }
            fntCheckStatus()
        }else{
            readXlsxFile(input.files[0]).then((rows) => {
                cantidadCeldasProcesadas = 0
                cantidadDatosFormateados = 0
                dataProcesada = null
                fntSearchFicha(rows)
                if (fntCheckStatus()) {
                    fntSearchHorarios(rows)
                    if (horario == null) {
                        Swal.fire({
                            title: "Error",
                            text: "No se lograron obtener datos",
                            icon: "error"
                        });
                    }else{
                        dataProcesada = procesData(horario)
                        printForms()
                        printAlert()
                    }
                }
            })
        }
    }
})

function fntCheckStatus(){
    let response
    if (fileStatus.code > 0) {
        Swal.fire({
            title: "Error",
            text: fileStatus.msg,
            icon: "error"
          });
        response = false
    }else{
        response = true
    }
    return response
}

function fntSearchFicha(rows){

    rows.forEach(row => {
        row.forEach((cell, index, arrRow) =>{
            if (cell == "Ficha") {
                ficha = arrRow[index+1]
            }
        })
    })

    if(ficha == null) {
        fileStatus = {
            code: 2,
            msg:"No se pudo encontrar el numero de ficha"
        }
    }
}

function fntSearchHorarios(rows){
    let mesEncontrado = null
    let contadorDia = 0
    let contadorDiaHorario = 0
    let mesPlaceholder
    let hold = false
    let diaEncontrado = []
    let horasEncontrada = []
    let fecha
    let horaInicio
    let horaFin
    let contenido
    let contadorHorario = 0
    let filaDelMes = 0
    cantidadCeldasProcesadas = rows.length

    rows.forEach((row, rowNumber, arrRowCell) => {

        row.forEach((cell, cellNumber, arrRow) =>{

            meses.forEach(mes =>{

                if (cell === mes) {
                    filaDelMes = rowNumber
                    contadorDia = 0
                    diaEncontrado = []
                }
                if (cell === mes || arrRowCell[filaDelMes][cellNumber] === mes) {
                    mesEncontrado = mes
                }
            })

            if (mesEncontrado != null) {
                if (Number.isInteger(cell)) {
                    
                    if (contadorDia > 5) {
                        horasEncontrada.push(cell)
                        contadorDiaHorario = 0
                    }else{
                        if (hold && cell == 1) {
                            mesEncontrado = mesPlaceholder
                            hold = false
                        }
                        //lee los dias validos
                        let celdaInferior
                        try{
                            celdaInferior = arrRowCell[rowNumber+1][cellNumber]
                        }catch{
                            celdaInferior = null
                        } 

                        if (celdaInferior != null && celdaInferior != 'FESTIVO') {
                            diaEncontrado.push(cell)
                        }
                        contadorDia++
                    }
                }else if(cell != null && cell != 'FESTIVO' && !fntCompareDays(cell) && !fntCompareMounths(cell)){

                    fecha = diaEncontrado[contadorDiaHorario] + '/' + (meses.findIndex(m => m == mesEncontrado)+1) + '/' + date.getFullYear()

                    horaInicio = horasEncontrada[horasEncontrada.length-2]
                    horaFin = horasEncontrada[horasEncontrada.length-1]
                    let contenidoFormateado = cell.replace(/\/n/gi, ",")
                    contenido = contenidoFormateado.split(/\r?\n/)

                    horario[contadorHorario] = {
                        fecha:fecha,
                        horaInicio:horaInicio,
                        horaFin:horaFin,
                        instructor:contenido[0],
                        coordenada:{
                            fila: rowNumber+1,
                            celda_index: cellNumber,
                            celda_letra: celdas[cellNumber]
                        }
                    }
                    contadorHorario++
                    contadorDiaHorario++
                }
            }

        })

    })
}

function fntCompareDays(data){
    respuesta = false
    diasSemana.forEach(dia =>{
        if (data == dia) {
            respuesta = true
        }
    })
    return respuesta
}

function fntCompareMounths(data){
    respuesta = false
    meses.forEach(mes =>{
        if (data == mes) {
            respuesta = true
        }
    })

    return respuesta
}

function printAlert(){
    let cantidadRegistros = 0
    Object.entries(horario).forEach(row => cantidadRegistros++)
    
    html = `
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <button id="btnGuardarHorario" class="btn btn-primary">Guardar Todo</button> 
        <button type="button" class="btn btn-secondary" data-action="datosValidos">
            Datos validados <span class="badge text-bg-primary">${cantidadRegistros}</span>
        </button>
        <button type="button" class="btn btn-secondary" data-action="datosOrganizados">
            Datos organizados <span class="badge text-bg-primary">${cantidadDatosFormateados}</span>
        </button>
        Cantidad de celdas procesadas: <b>${cantidadCeldasProcesadas}</b>
    </div>
    `
    alertZone.innerHTML = html
}

function fntPrintHorario(){

    let div = document.createElement('div')
    let table = document.createElement('table')
    let thead = document.createElement('thead')
    let tbody = document.createElement('tbody')
    let th = document.createElement('th')
    let tr = document.createElement('tr')

    table.classList.add('table')

    let node = document.createTextNode('Fecha')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)
    

    node = document.createTextNode('Hora inicio')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)

    node = document.createTextNode('Hora fin')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)

    node = document.createTextNode('Instructor')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)

    node = document.createTextNode('Coordenada')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)

    thead.appendChild(tr)
    table.appendChild(thead)
    table.appendChild(tbody)

    Object.entries(horario).forEach(row =>{
        dataRow = row[1]

        tbody.innerHTML += `
        <tr>
            <td>${dataRow.fecha}</td>
            <td>${dataRow.horaInicio}</td>
            <td>${dataRow.horaFin}</td>
            <td>${dataRow.instructor}</td>
            <td>${dataRow.coordenada.fila} | ${dataRow.coordenada.celda_letra}</td>
        </tr>
        `
        
    })
    div.appendChild(table)
    displayModal.innerHTML = ""
    displayModal.appendChild(div)
}

function sentData(){
    const frmHorario = document.querySelector('#frmHorario')
    let frmData = new FormData(frmHorario)

    fetch(base_url + '/horario/setHorario',{
        method: "POST",
        body: frmData,
    })
    .then((res)=>res.json())
    .then((data) => {
        console.log(data)
        let dataStatus = 'question'
        switch (data.statusCode) {
            case 0:
                dataStatus = 'success'
                break;
            case 1:
                dataStatus = 'warning'
                break;
            case 2:
                dataStatus = 'error'
                break;
            case 3:
                dataStatus = 'error'
                break;
        }

        Swal.fire({
            title: 'Insertar Horario',
            text: data.msg,
            icon: dataStatus
        });
        displayErrors(data.log)
    })

}

 function procesData(data){
    let respuestaRaw = {}
    let arrFechas = []
    let respuesta = {}
    Object.entries(data).forEach(row =>{
        dataRow = row[1]
        arrFechas.push(dataRow.fecha)       
    })
    let uniqArrFechas = [...new Set(arrFechas)]
    //FIX IT!!!!
    uniqArrFechas.forEach((fecha, fechaIndex) => {
        let contenido = {}
        let indexCout = 0
        Object.entries(data).forEach((row) =>{
            dataRow = row[1]

            if (fecha == dataRow.fecha) {
                contenido[indexCout] = {
                    horaInicio:dataRow.horaInicio,
                    horaFin:dataRow.horaFin,
                    instructor:dataRow.instructor
                }
                indexCout++
            }  
        })

        respuestaRaw[fecha] = contenido
    })

    let letResponseIndex = 0
    Object.entries(respuestaRaw).forEach((data) =>{
        
        //console.log(data)
        let resFecha = data[0]
        let resHoraInicio
        let resHoraFin
        let resInstructor
        let resContenido = data[1]
        
        if (resContenido[0].instructor == resContenido[5].instructor) {
            resHoraInicio = resContenido[0].horaInicio
            resHoraFin = resContenido[5].horaFin
            resInstructor = resContenido[0].instructor

            respuesta[letResponseIndex] = {
                fecha:resFecha,
                horaInicio:resHoraInicio,
                horaFin:resHoraFin,
                instructor:resInstructor
            }
    
            letResponseIndex++
        }else{
            //primer bloque
            resHoraInicio = resContenido[0].horaInicio
            resHoraFin = resContenido[2].horaFin
            resInstructor = resContenido[0].instructor

            respuesta[letResponseIndex] = {
                fecha:resFecha,
                horaInicio:resHoraInicio,
                horaFin:resHoraFin,
                instructor:resInstructor
            }
    
            letResponseIndex++
            
            //segundo bloque
            resHoraInicio_bloque2 = resContenido[3].horaInicio
            resHoraFin_bloque2 = resContenido[5].horaFin
            resInstructor_bloque2 = resContenido[3].instructor
            
            respuesta[letResponseIndex] = {
                fecha:resFecha,
                horaInicio:resHoraInicio_bloque2,
                horaFin:resHoraFin_bloque2,
                instructor:resInstructor_bloque2
            }
            letResponseIndex++
        }
    })

    return respuesta
} 

function printProcesData(){
    let html = ""
    Object.entries(dataProcesada).forEach(data => {
        data = data[1]
        html += `
            <tr>
                <td>${data.fecha}</td>
                <td>${data.horaInicio}</td>
                <td>${data.horaFin}</td>
                <td>${data.instructor}</td>
            </tr>
        `;
        cantidadDatosFormateados++
    })

    displayModal.innerHTML = `
    
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora inicio</th>
                        <th>Hora fin</th>
                        <th>Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    ${html}
                </tbody>
            </table>
        </div>
    </div>

    `
}

function printForms(){
    let html = `
    <form id="frmHorario">
        <input type="hidden" class="form-control" name="ficha" value="${ficha}">
    `

    Object.entries(dataProcesada).forEach((data, index) => {
        data = data[1]

        html += `

        <div class="card">
            <div class="card-body">
                <div class="row">
                
                <div class="mt-2"></div>
                <div id="dataCard_${index}"></div>
                <div class="mb-1"></div>

                    <div class="col-lg-6">
                        <div class="mb-3 col-12">
                            <label for="txtNombre" class="form-label">Fecha</label>
                            <input type="text" class="form-control" name="hFecha[]" value="${data.fecha}">
                        </div>

                        <div class="mb-3 col-12">
                            <label for="txtNombre" class="form-label">Instructor</label>
                            <input type="text" class="form-control" name="hInstructor[]" value="${data.instructor}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3 col-6">
                            <label for="txtNombre" class="form-label">Hora Inicio</label>
                            <input type="text" class="form-control" name="hHoraInicio[]" value="${data.horaInicio}">
                        </div>

                        <div class="mb-3 col-6">
                            <label for="txtNombre" class="form-label">Hora Fin</label>
                            <input type="text" class="form-control" name="hHoraFin[]" value="${data.horaFin}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
        cantidadDatosFormateados++
    })

    html += `
    </form>`

    display.innerHTML = html
}

function displayErrors(log){
    log.forEach((error, index) => {
        let display = document.querySelector(`#dataCard_${index}`)
        display.innerHTML = error;
        console.log(error)
    })
}


/*

            <li class="list-group-item">
                <div class="mb-3 col-12">
                    <label for="txtNombre" class="form-label">Fecha</label>
                    <input type="text" class="form-control" name="hFecha[]" value="${data.fecha}">
                </div>
                <div class="mb-3 col-6">
                    <label for="txtNombre" class="form-label">Hora Inicio</label>
                    <input type="text" class="form-control" name="hHoraInicio[]" value="${data.horaInicio}">
                </div>
                <div class="mb-3 col-6">
                    <label for="txtNombre" class="form-label">Hora Fin</label>
                    <input type="text" class="form-control" name="hHoraFin[]" value="${data.horaFin}">
                </div>
                <div class="mb-3 col-12">
                    <label for="txtNombre" class="form-label">Instructor</label>
                    <input type="text" class="form-control" name="hInstructor[]" value="${data.instructor}">
                </div>
            </li>


                            <span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i> Danger</span>

*/

