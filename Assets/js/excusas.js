let btnAgregarExcusa = document.querySelector('#btnCrearExcusa');
let tablaExcusas = document.querySelector('#tablaExcusas');


btnAgregarExcusa.addEventListener('click',() => {
    $('#crearExcusaModal').modal('show')
    console.log('dss')
})

cargarTabla()
function cargarTabla() {
    tablaExcusas = $('#tablaExcusas').dataTable({
        "language": {
            "url": `${base_url}/Assets/vendor/datatables/dataTables_es.json`
        },
        "ajax":{
            "url": " "+base_url+"/excusas/getExcusas",
            "dataSrc":""
        },
        "columns":[
            {"data":"fechaCompleta"},
            {"data":"nombreCompleto"},
            {"data":"status"},
            {"data":"action"},
        ],
        "responsive": "true",
        "iDisplayLength": 10,
        "order":[[0, "asc"]]
    }) 
}
