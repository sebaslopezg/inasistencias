let infoAprendiz = document.querySelector("#infoAprendiz");
let tableVisibility = document.querySelector("#tabla-informe");
let mostrarInfo = document.querySelector("#mostrar-info");
let btnCerrarModal = document.querySelector("#btnCerrarModal");
let btnAsistencia = document.querySelector("#btnAsistencia");
let btnInasistencia = document.querySelector("#btnInasistencia");
let mostrarBtn = document.querySelector("#mostrar-btn");
let btnPdf = document.querySelector("#btnPdf");
let btnPdfM = document.querySelector("#btnPdfmodal");
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

/*
btnPdf.addEventListener("click", () => {
  fetch(base_url + "/informes/generarPdf/pdfAsistencia/" + id_Ficha)
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      
    });
  });
});
*/

btnAsistencia.addEventListener("click", () => {
  cardInforme.style.display = "none";
  cardAsistencias.style.display = "block";
  informeAsistencia.style.display = "block";
  btnAsistencia.style.display = "none";
  btnInasistencia.style.display = "block";
  btnPdf.style.display = "block";
});

$(document).on("click", "#filtroFecha", function () {
  let info = document.getElementById(filtroFecha).value;

  console.log("click fecha: " + info);
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
  let i = 0;
  $(`#tabla-asistencia tr${i + 1}`).each(function () {
    $(`#aprendiz-tr`).remove();
    $("#colum-fecha").remove();
    $("#colum-info-ficha").remove();
  });
});

document.addEventListener("click", (e) => {
  try {
    let action = e.target.closest("button").getAttribute("data-action");
    let idAprendiz = e.target.closest("button").getAttribute("data-id");

    if (action === "info") {
      fetch(base_url + "/informes/getFaltas/" + idAprendiz)
        .then((res) => res.json())
        .then((data) => {
          let elemento = `
           <button type="button" style="float:right; margin-right:5px;" id="btnPDFmodal" data-action="pdf" data-id="${idAprendiz}"  class="btn btn-outline-danger mb-3"> <i class="bi bi-filetype-pdf" style="font-size:larger;"></i></button>
          `;
          mostrarBtn.innerHTML = elemento;
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
    } else if (action === "pdf") {
      let idAprendiz = e.target.closest("button").getAttribute("data-id");
      fetch(base_url + "/informes/generarPdf/" + idAprendiz)
        .then((res) => res.json())
        .then((data) => {
          console.log(data);
        });
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
          nombreFicha: "" + data.nombre_ficha + "",
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
      agregarFicha(
        ui.item.value,
        ui.item.label,
        ui.item.numeroFicha,
        ui.item.nombreFicha
      );
      // Limpia el campo de entrada después de que se haya seleccionado un Ficha
      $("#ficha").val("");
      return false;
    },
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
        if ($(this).find("[name='idFicha[]']").val() == idFicha) {
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
          text: "La Ficha ya está selecionada en la tabla de Fichas.",
        });
      } else {
        // traemos los APRENDICES disponibles para asignarlos a la ficha Seleccionada.

        fetch(base_url + "/informes/getInfoAprendicesFicha/" + idFicha)
          .then((res) => res.json())
          .then((data) => {
            data.forEach((data) => {
              let texto = ` <tr id="aprendiz-tr">
              <td>${data.nombre_completo}</td><td>${data.correo}</td><td style="text-align: center;">${data.faltas}</td><td style="text-align: center;">${data.accion}</td>
              </tr>  `;
              infoAprendiz.innerHTML += texto;
            });
          });

        let tr = document.createElement("tr");
        let th1 = document.createElement("th");
        let th2 = document.createElement("th");
        let text1 = document.createTextNode(`${nombreFicha}`);
        let text2 = document.createTextNode(`${numeroFicha}`);

        th1.appendChild(text1);
        th1.setAttribute("style", "ont-size: large; text-align: center;");
        th2.appendChild(text2);
        th2.setAttribute("style", "ont-size: large; text-align: center;");
        tr.appendChild(th1);
        tr.appendChild(th2);
        $("#tabla-infoFicha tbody").append(tr);
        tablaAsistencias();
      }
    } else {
      Swal.fire({
        icon: "warning",
        title: "¡ Ya hay una ficha selecionada !",
        text: "Elimina la ficha anterior, para eligir una nueva ficha.",
      });
    }
  }
  function tablaAsistencias() {
    // -----------------------------------
    //   TRAEMOS LAS FECHAS DEL HORARIO DEL INSTRUCTOR
    // -----------------------------------

    fetch(base_url + "/informes/getFechaInstructor/" + codigoFicha)
      .then((res) => res.json())
      .then((data) => {
        data.forEach((data) => {
          let th = document.createElement("th");
          let text = document.createTextNode(`${data.fechaInicio}`);
          th.appendChild(text);
          th.setAttribute("scope", "col");
          th.setAttribute("style", "text-align: center;");
          th.setAttribute("id", "colum-fecha");
          fechaTr.appendChild(th);
        });
      });

    // -----------------------------------
    //    TRAEMOS LOS NOMBRES DE LOS APRENDICES DE LA TABLA ASISTENCIAS
    // -----------------------------------

    let nombres = [];
    fetch(base_url + "/informes/getAprendices/" + id_Ficha)
      .then((res) => res.json())
      .then((data) => {
        data.forEach((data) => {
          nombres.push(data.nombre_completo);
        });
      });

    // -----------------------------------
    //  MOSTRAMOS LA CONTROL DE ASISTENCIAS DE LA FICHA
    // -----------------------------------

    fetch(base_url + "/informes/getAsistencia/" + id_Ficha)
      .then((res) => res.json())
      .then((data) => {
        let info = [];
        for (let i = 0; i < nombres.length; i++) {
          info = data.filter(
            (aprendiz) => aprendiz.nombre_completo === `${nombres[i]}`
          );

          let fila = `
          <tr id="aprendiz-tr${i}">
          <td scope="col" style="text-align: center;">${i + 1} </td>
          <td scope="col" style="text-align: center;">${nombres[i]}</td>
          </tr>
          `;
          //console.log(info);
          columAprendiz.innerHTML += fila;
          for (let index = 0; index < info.length; index++) {
            let celda = ` <td scope="col" name="col-fecha" style="text-align: center;">${info[index].status}</td>`;
            $(`#aprendiz-tr${i}`).append(celda);
          }
        }
      });
  }

  $(document).on("click", ".eliminar-fila", function () {
    $(this).closest("tr").remove();

    // ------------------------------------------------
    //    DESHABILITAMOS LOS ELEMENTOS (BTN AND TABLE)
    //  -----------------------------------------------

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

    let i = 0;
    let indice = 0;
    $(`#tabla-asistencia tr${i + 1}`).each(function () {
      $(`#aprendiz-tr${indice + 1}`).remove();
      $("#colum-fecha").remove();
      $("#colum-info-ficha").remove();
    });
    $("#tabla-aprendices tr").each(function () {
      $("#instru-tr").remove();
    });
  });
});
