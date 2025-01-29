let tablaInfoAprendiz = document.querySelector("#tabla-aprendices");
let tableVisibility = document.querySelector("#tabla-informe");
let mostrarInfo = document.querySelector("#mostrar-info");
let btnCerrarModal = document.querySelector("#btnCerrarModal");
let btnAsistencia = document.querySelector("#btnAsistencia");
let btnInasistencia = document.querySelector("#btnInasistencia");
let btnPdf = document.querySelector("#btnPdf");
let cardInforme = document.querySelector(".class-informes");
let cardAsistencias = document.querySelector("#cardAsistencias");
let informeAsistencia = document.querySelector("#informe-asistencia");
let tablaAsistencias = document.querySelector("#tabla-asistencia");
let fechaTr = document.querySelector("#fecha-tr");
let columAprendiz = document.querySelector("#colum-aprendiz");
let codigoFicha = 0;
let id_Ficha = 0;

// -----------------------------------
//             BOTONES
// -----------------------------------

btnCerrarModal.addEventListener("click", () => {
  mostrarInfo.innerHTML = "";
});
btnAsistencia.addEventListener("click", () => {
  cardInforme.style.display = "none";
  cardAsistencias.style.display = "block";
  informeAsistencia.style.display = "block";
  btnAsistencia.style.display = "none";
  btnInasistencia.style.display = "block";
  btnPdf.style.display = "block";

  //
  fetch(base_url + "/informes/getFechaInstructor/" + codigoFicha)
    .then((res) => res.json())
    .then((data) => {
      data.forEach((data) => {
        let fila = `
           <th scope="col" class="cosa" id="colum-fecha" style="text-align: center;"  > ${data.fechaInicio}</th> `;
        fechaTr.innerHTML += fila;
      });
    });

  fetch(base_url + "/informes/getAsistencia/" + id_Ficha)
    .then((res) => res.json())
    .then((data) => {
      const info = data.filter((aprendiz) => aprendiz.fecha === "2025-10-17");

      data.forEach((data) => {
        let fila = `
        <tr id="aprendiz-tr" >
        <td scope="col" style="text-align: center;">${data.id} </td>
        <td scope="col" style="text-align: center;">${data.nombre_completo}</td>
        <td scope="col" style="text-align: center;">${data.status}</td>
        </tr>
        `;
        columAprendiz.innerHTML += fila;
      });
    });
});

btnInasistencia.addEventListener("click", () => {
  // -----------------------------------
  //    DESHABILITAMOS LOS ELEMENTOS (BTN AND TABLE)
  //  -----------------------------------
  cardInforme.style.display = "block";
  cardAsistencias.style.display = "none";
  informeAsistencia.style.display = "none";
  btnAsistencia.style.display = "block";
  btnInasistencia.style.display = "none";
  btnPdf.style.display = "none";

  // -----------------------------------
  //    LIMPIAMOS LAS TABLAS
  //  -----------------------------------
  $("#tabla-asistencia tr").each(function () {
    $("#aprendiz-tr").remove();
    $("#colum-fecha").remove();
  });
});
document.addEventListener("click", (e) => {
  try {
    let action = e.target.closest("button").getAttribute("data-action");
    let id = e.target.closest("button").getAttribute("data-id");

    if (action == "info") {
      fetch(base_url + "/informes/getFaltas/" + id)
        .then((res) => res.json())
        .then((data) => {
          data.forEach((data) => {
            let row = `
            <div class="row">
                     <div class="mb-1 col-6">
                         <label for="fecha" class="form-label"> <b>Nombre - Aprendiz</b> </label>
                         <div class="card">
                             <div class="card-body">
                                 <ul class="list-group mt-3 ">
                                     <li class="list-group-item" style="background-color:#e9ecef;"> ${data.nombre_completo}</li>
                                 </ul>
                             </div>
                         </div>
                     </div>
                     <div class="mb-2 col-6">
                         <label for="fecha" class="form-label"><b>Fecha - Inasistencia</b> </label>
                         <div class="card">
                             <ul class="list-group mt-3 ">
                                   <li class="list-group-item" style="background-color:#e9ecef;"> ${data.fecha}</li>
                             </ul>
                         </div>
                     </div>
                 </div>
           `;
            mostrarInfo.innerHTML += row;
          });
        });
      $("#modalInfo").modal("show");
    }
  } catch {}
});

$(document).ready(function () {
  // -----------------------------------
  //    VERIFICAR Fichas DISPONIBLES
  // -----------------------------------
  let availableFichas = [];
  fetch(base_url + "/informes/getFichas")
    .then((res) => res.json())
    .then((data) => {
      data.forEach((data) => {
        let fila = {
          label: "" + data.nombre_ficha + " - " + data.numeroFicha,
          value: "" + data.id + "",
          numeroFicha: "" + data.numeroFicha + "",
          nombreFicha: "" + data.nombre_ficha + ""
        };
        availableFichas.push(fila);
      });
    });

  // -----------------------------------
  //    AUTOCOMPLETADO DE FICHAS
  // -----------------------------------

  $("#ficha").autocomplete({
    // Establece como fuente de sugerencias el array "availableFichas", que contiene productos disponibles
    source: availableFichas,
    // event: Representa el evento JavaScript que desencadena la función select.
    // ui: Es un objeto de jQuery UI que contiene información específica sobre el elemento seleccionado en la lista de autocompletado.
    // Define la función que se ejecuta al seleccionar un producto de la lista de autocompletado
    select: function (event, ui) {
      // Pasa el idFicha (ui.item.value), el nombre(ui.item.label), el numeroFicha (ui.item.precio), como argumentos.
      agregarFicha(ui.item.value, ui.item.label, ui.item.numeroFicha, ui.item.nombreFicha);
      // Limpia el campo de entrada después de que se haya seleccionado un Ficha
      $("#ficha").val("");
      return false;
    }
  });

  function agregarFicha(idFicha, label, numeroFicha, nombreFicha) {
    // Verificar que solo haya eligido 1 Ficha, para evitar Cruce de Informacion.
    if ($("#tabla-infoFicha tbody tr").length < 1) {
      tableVisibility.style.display = "block";
      btnAsistencia.style.display = "block";
      codigoFicha = numeroFicha;
      id_Ficha = idFicha;
      // Verificar si la Ficha ya está en la tabla
      let fichaYaAgregado = false;
      // Itera sobre cada fila de la tabla para comprobar si el ID del ficha ya existe
      $("#tabla-infoFicha tbody tr").each(function () {
        if ($(this).find("input[name='idFicha[]']").val() == idFicha) {
          // Si encuentra una coincidencia de ID, marca fichaYaAgregado como true
          fichaYaAgregado = true;
          // Sale del ciclo
          return false;
        }
      });

      // Si la ficha ya está en la tabla, muestra una alerta usando SweetAlert
      if (fichaYaAgregado) {
        Swal.fire({
          icon: "warning",
          title: "Ficha ya agregada ",
          text: "La Ficha ya está selecionada en la tabla de Fichas."
        });
      } else {
        // traemos los APRENDICES disponibles para asignarlos a la ficha Seleccionada.

        fetch(base_url + "/informes/getInfoAprendicesFicha/" + idFicha)
          .then((res) => res.json())
          .then((data) => {
            data.forEach((data) => {
              let texto = ` <tr id="instru-tr"><td>${data.nombre_completo}</td><td>${data.correo}</td><td style="text-align: center;">${data.faltas}</td><td style="text-align: center;">${data.accion}</td></tr>  `;
              tablaInfoAprendiz.innerHTML += texto;
            });
          });

        let filaFicha = `
            <tr id="ficha-tr" >
              <td style="font-size: large; text-align: center;">${nombreFicha}</td>
              <td style="font-size: large; text-align: center;">${numeroFicha}</td>
           </tr>
        `;
        $("#tabla-infoFicha tbody").append(filaFicha);
      }
    } else {
      Swal.fire({
        icon: "warning",
        title: "¡ Ya hay una ficha selecionada !",
        text: "Elimina la ficha anterior, para eligir una nueva ficha."
      });
    }
  }
  $(document).on("click", ".eliminar-fila", function () {
    $(this).closest("tr").remove();

    // -----------------------------------
    //    DESHABILITAMOS LOS ELEMENTOS (BTN AND TABLE)
    //  -----------------------------------

    tableVisibility.style.display = "none";
    btnAsistencia.style.display = "none";
    btnInasistencia.style.display = "none";
    btnPdf.style.display = "none";

    // -----------------------------------
    //    LIMIPIAMOS LAS TABLAS CON LA INFORMACION IMPRESA
    // -----------------------------------
    $("#tabla-infoFicha tr").each(function () {
      $("#ficha-tr").remove();
    });

    $("#tabla-asistencia tr").each(function () {
      $("#aprendiz-tr").remove();
      $("#colum-fecha").remove();
      $("#colum-info-ficha").remove();
    });
    $("#tabla-aprendices tr").each(function () {
      $("#instru-tr").remove();
    });
  });
});
