<?php
if(isset($_GET['factura'])) {
    $facturaData = json_decode(urldecode($_GET['factura']), true);
    print_r($facturaData); // Verificar que los datos llegan correctamente
    exit; // Solo para prueba, quitar después
}

if(isset($_SESSION['facturaParaReordenar'])) {
    $facturaData = json_decode($_SESSION['facturaParaReordenar'], true);
    // Usar $facturaData para generar el PDF
    unset($_SESSION['facturaParaReordenar']); // Limpiar después de usar
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organizar PDF como iLovePDF</title>
  <link rel="stylesheet" href="../css/style.min.css">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <h2>Organiza tu PDF</h2>

  <div id="drop-zone">
    <p>Arrastra y suelta un archivo PDF aquí, o haz clic para seleccionar.</p>
    <input type="file" id="pdf-upload" accept="application/pdf">
  </div>

  <div id="file-name-display" class="status-message hidden"></div>

  <div id="thumbnails">
  </div>

  <div id="controls" class="hidden">
    <button onclick="console.log('Botón clickeado')" id="assign-order-btn" onclick="assignOrder()">Reordenar y Previsualizar</button>
    <div id="preview"></div>
    <button id="download-btn"  disabled>Descargar PDF Reordenado</button>
    <a class="buttom" href="../../dashboard/armador2.php">volver al armador</a>
  </div>


  <div id="loading-message" class="status-message hidden">Cargando PDF...</div>

  <!-- En tu vista de reordenación -->
   <div id="error-msg">
        <h1  style="color: crimson; text-align: center; display: auto;">
  ⚠️ Debes cargar una factura para reordenarla.
        </h1>

      <a href="../../dashboard/armador2.php">Regresar</a>
   </div>
    
    



  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js">
    document.getElementById('error-msg').style.display = 'block';

  </script>
  <script src="https://unpkg.com/pdf-lib/dist/pdf-lib.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>


  <script>


// Nuevo: cargar automáticamente PDF si viene por parámetro GET
window.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const archivo = params.get("archivo");

  if (archivo) {
    fetch(`../facturas/${archivo}`)
      .then(response => {
        if (!response.ok) {
          throw new Error("No se pudo cargar el PDF.");
        }
        return response.blob();
      })
      .then(blob => {
        const file = new File([blob], archivo, { type: "application/pdf" });
        handleFile(file); // Usa la misma función del input manual
      })
      .catch(err => {
        alert("Error al cargar el PDF: " + err.message);
      });
  }
});



    // Configuración global para el worker de pdf.js
    let originalPdfBytes = null;
    let reorderedPdfBytes = null;
    let pageOrder = [];
    let currentSortableInstance = null;

    const pdfjsLib = window['pdfjs-dist/build/pdf'];
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('assign-order-btn').addEventListener('click', assignOrder);
      document.getElementById('download-btn').addEventListener('click', downloadReorderedPDF);
    });


    


    const dropZone = document.getElementById('drop-zone');
    const fileUploadInput = document.getElementById('pdf-upload');
    const thumbnailContainer = document.getElementById('thumbnails');
    const previewContainer = document.getElementById('preview');
    const assignOrderBtn = document.getElementById('assign-order-btn').disabled = false;
    const downloadBtn = document.getElementById('download-btn');
    const controlsDiv = document.getElementById('controls');
    const fileNameDisplay = document.getElementById('file-name-display');
    const loadingMessage = document.getElementById('loading-message');

    // Event listeners para la zona de arrastrar y soltar
    dropZone.addEventListener('click', () => fileUploadInput.click());
    dropZone.addEventListener('dragover', (event) => {
      event.preventDefault(); // Necesario para permitir el drop
      dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', () => {
      dropZone.classList.remove('dragover');
    });
    dropZone.addEventListener('drop', (event) => {
      event.preventDefault();
      dropZone.classList.remove('dragover');
      const files = event.dataTransfer.files;
      if (files.length > 0) {
        handleFile(files[0]);
      }
    });

    // Event listener para el input de archivo (cambio)
    fileUploadInput.addEventListener('change', (e) => {
      if (e.target.files.length > 0) {
        handleFile(e.target.files[0]);
      }
    });

    let archivoCargadoNombre = '';

    async function handleFile(file) {
      archivoCargadoNombre = file.name;
      if (!file) return;

      if (file.type !== "application/pdf") {
        alert("Por favor, selecciona un archivo PDF válido.");
        return;
      }

      fileNameDisplay.textContent = `Archivo seleccionado: ${file.name}`;
      fileNameDisplay.classList.remove('hidden');
      loadingMessage.classList.remove('hidden');
      controlsDiv.classList.add('hidden'); // Ocultar controles mientras se carga
      thumbnailContainer.innerHTML = '<div class="spinner"></div><p>Cargando miniaturas...</p>'; // Mensaje de carga para miniaturas
      previewContainer.innerHTML = ''; // Limpiar vista previa anterior
      downloadBtn.disabled = true; // Deshabilitar botón de descarga

      const fileReader = new FileReader();
      

      document.getElementById('error-msg').style.display = 'none';




      /*fileReader.onload = async (event) => {
        try {
          originalPdfBytes = event.target.result; // Esto es un ArrayBuffer

          // Para pdf.js, es bueno usar una copia si el buffer original se va a usar en otro lado (como con pdf-lib)
          // o si pdf.js pudiera modificarlo. .slice(0) crea una copia.
          const pdfJsData = originalPdfBytes.slice(0);
          const pdf = await pdfjsLib.getDocument({ data: pdfJsData }).promise;

          thumbnailContainer.innerHTML = ''; // Limpiar spinner de miniaturas
          pageOrder = []; // Resetear el orden de las páginas

          if (pdf.numPages === 0) {
            thumbnailContainer.innerHTML = '<p>El PDF no contiene páginas.</p>';
            loadingMessage.classList.add('hidden');
            return;
          }

     for (let i = 0; i < pdf.numPages; i++) {
            const page = await pdf.getPage(i + 1); // las páginas en pdf.js son 1-indexed
            


            const viewport = page.getViewport({ scale: 0.25 }); // Escala reducida para miniaturas más pequeñas

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            await page.render({ canvasContext: context, viewport: viewport }).promise;

            const wrapper = document.createElement('div');
            wrapper.className = 'page-thumbnail';
            wrapper.dataset.pageIndex = i; // Guardamos el índice original (0-indexed)
            wrapper.appendChild(canvas);

            const label = document.createElement('div');
            label.textContent = `Pág. ${i + 1}`;
            label.style.marginTop = '5px';
            wrapper.appendChild(label);

            thumbnailContainer.appendChild(wrapper);
            pageOrder.push(i); // Inicializar pageOrder con la secuencia original
          }

          // Destruir instancia anterior de SortableJS si existe
          if (currentSortableInstance) {
            currentSortableInstance.destroy();
          }
          // Inicializar SortableJS para permitir arrastrar y soltar las miniaturas
          currentSortableInstance = Sortable.create(thumbnailContainer, {
            animation: 150, // Animación al mover
            ghostClass: 'sortable-ghost', // Clase para el elemento fantasma
            chosenClass: 'sortable-chosen', // Clase para el elemento elegido
            dragClass: 'sortable-drag', // Clase para el elemento que se arrastra
            onEnd: () => {
              // Actualizar pageOrder cuando el usuario termina de arrastrar
              const thumbnails = thumbnailContainer.querySelectorAll('.page-thumbnail');
              pageOrder = Array.from(thumbnails).map(div => parseInt(div.dataset.pageIndex));
              console.log("Orden de páginas actualizado por Sortable:", pageOrder);
              downloadBtn.disabled = true; // Requerir nueva asignación de orden para descargar
              previewContainer.innerHTML = '<p class="status-message">Orden cambiado. Haz clic en "Reordenar y Previsualizar".</p>';
            }
          });
          controlsDiv.classList.remove('hidden'); // Mostrar controles
        } catch (error) {
          console.error("Error al cargar y procesar el PDF:", error);
          alert("Error al cargar el PDF. Asegúrate de que es un archivo PDF válido y no está corrupto.");
          thumbnailContainer.innerHTML = '<p>Error al cargar miniaturas. Intenta con otro archivo.</p>';
          fileNameDisplay.classList.add('hidden');
        } finally {
          loadingMessage.classList.add('hidden');
          fileUploadInput.value = ''; // Reset file input to allow re-uploading the same file
        }
      };

                    **** SEGUNDO BLOQUE *****

          fileReader.onload = async (event) => {
          try {
            console.log("Leyendo archivo...");
            originalPdfBytes = event.target.result;

            if (!originalPdfBytes || originalPdfBytes.byteLength === 0) {
              throw new Error("El archivo está vacío.");
            }

            console.log("Cargando con PDF.js...");
            const pdfJsData = originalPdfBytes.slice(0);
            console.log("Bytes del archivo PDF:", originalPdfBytes.byteLength);
            const pdf = await pdfjsLib.getDocument({ data: pdfJsData }).promise;
            console.log("Total páginas:", pdf.numPages);

            thumbnailContainer.innerHTML = ''; // limpiar zona de miniaturas
            pageOrder = []; // reiniciar orden

            for (let i = 0; i < pdf.numPages; i++) {
              const page = await pdf.getPage(i + 1);
              console.log(`Renderizando página ${i + 1}...`);

              const viewport = page.getViewport({ scale: 0.8 }); // mejor resolución
              const canvas = document.createElement('canvas');
              const context = canvas.getContext('2d');
              canvas.height = viewport.height;
              canvas.width = viewport.width;

              try {
                await page.render({ canvasContext: context, viewport }).promise;
                console.log(`Página ${i + 1} renderizada.`);
              } catch (renderErr) {
                console.error(`Error al renderizar página ${i + 1}:`, renderErr);
                continue;
              }

              const wrapper = document.createElement('div');
              wrapper.className = 'page-thumbnail';
              wrapper.dataset.pageIndex = i;
              wrapper.appendChild(canvas);

              const label = document.createElement('div');
              label.textContent = `Pág. ${i + 1}`;
              label.style.marginTop = '5px';
              wrapper.appendChild(label);

              thumbnailContainer.appendChild(wrapper);
              pageOrder.push(i);
            }

            // Iniciar Sortable si hay más de una página
            if (currentSortableInstance) {
              currentSortableInstance.destroy();
            }
            currentSortableInstance = Sortable.create(thumbnailContainer, {
              animation: 150,
              onEnd: () => {
                const thumbnails = thumbnailContainer.querySelectorAll('.page-thumbnail');
                pageOrder = Array.from(thumbnails).map(div => parseInt(div.dataset.pageIndex));
                console.log("Nuevo orden:", pageOrder);
                downloadBtn.disabled = true;
                previewContainer.innerHTML = '<p class="status-message">Orden cambiado. Haz clic en "Reordenar y Previsualizar".</p>';
              }
            });

            controlsDiv.classList.remove('hidden');
            loadingMessage.classList.add('hidden');

          } catch (error) {
            console.error("Error al cargar el PDF:", error);
            alert("Error al cargar el PDF: " + error.message);
            loadingMessage.classList.add('hidden');
          }
        };*/








        fileReader.onload = async (event) => {
        originalPdfBytes = event.target.result;
  try {
    console.log("Leyendo archivo...");
    originalPdfBytes = event.target.result;

    if (!originalPdfBytes || originalPdfBytes.byteLength === 0) {
      throw new Error("El archivo está vacío.");
    }

    console.log("Cargando con PDF.js...");
    const pdfJsData = originalPdfBytes.slice(0);
    console.log("Bytes del archivo PDF:", originalPdfBytes.byteLength);
    const pdf = await pdfjsLib.getDocument({ data: pdfJsData }).promise;
    console.log("Total páginas:", pdf.numPages);

    // Limpiar el área de miniaturas
    thumbnailContainer.innerHTML = '';
    pageOrder = [];

    if (pdf.numPages === 0) {
      thumbnailContainer.innerHTML = '<p>El PDF no contiene páginas.</p>';
      loadingMessage.classList.add('hidden');
      return;
    }

    for (let i = 0; i < pdf.numPages; i++) {
      const page = await pdf.getPage(i + 1);
      console.log(`Renderizando página ${i + 1}...`);

      const viewport = page.getViewport({ scale: 0.8 }); // buena calidad
      const canvas = document.createElement('canvas');
      const context = canvas.getContext('2d');
      canvas.height = viewport.height;
      canvas.width = viewport.width;

      try {
        await page.render({ canvasContext: context, viewport }).promise;
        console.log(`Página ${i + 1} renderizada.`);
      } catch (renderErr) {
        console.error(`Error al renderizar página ${i + 1}:`, renderErr);
        continue;
      }

      const wrapper = document.createElement('div');
      wrapper.className = 'page-thumbnail';
      wrapper.dataset.pageIndex = i;
      wrapper.appendChild(canvas);

      const label = document.createElement('div');
      label.textContent = `Pág. ${i + 1}`;
      label.style.marginTop = '5px';
      wrapper.appendChild(label);

      thumbnailContainer.appendChild(wrapper);
      pageOrder.push(i);
    }

    // Destruir e iniciar Sortable
    if (currentSortableInstance) {
      currentSortableInstance.destroy();
    }

    currentSortableInstance = Sortable.create(thumbnailContainer, {
      animation: 150,
      ghostClass: 'sortable-ghost',
      chosenClass: 'sortable-chosen',
      dragClass: 'sortable-drag',
      onEnd: () => {
        const thumbnails = thumbnailContainer.querySelectorAll('.page-thumbnail');
        pageOrder = Array.from(thumbnails).map(div => parseInt(div.dataset.pageIndex));
        console.log("Orden de páginas actualizado:", pageOrder);
        downloadBtn.disabled = true;
        previewContainer.innerHTML = '<p class="status-message">Orden cambiado. Haz clic en "Reordenar y Previsualizar".</p>';
      }
    });

    controlsDiv.classList.remove('hidden');
  } catch (error) {
    console.error("Error al cargar y procesar el PDF:", error);
    alert("Error al cargar el PDF: " + error.message);
    thumbnailContainer.innerHTML = '<p>Error al cargar miniaturas. Intenta con otro archivo.</p>';
    fileNameDisplay.classList.add('hidden');
  } finally {
    loadingMessage.classList.add('hidden');
    fileUploadInput.value = ''; // para permitir subir el mismo archivo otra vez
  }
};








      fileReader.onerror = (error) => {
        console.error("Error al leer el archivo:", error);
        alert("Ocurrió un error al leer el archivo PDF.");
        loadingMessage.classList.add('hidden');
        thumbnailContainer.innerHTML = '';
        fileNameDisplay.classList.add('hidden');
      };

      fileReader.readAsArrayBuffer(file); // Leer el archivo como ArrayBuffer
    }

      window.assignOrder = async function assignOrder() {
      if (!originalPdfBytes || pageOrder.length === 0) {
        alert("Primero sube y organiza un archivo PDF.");
        return;
      }

      previewContainer.innerHTML = '<div class="spinner"></div><p class="status-message">Generando vista previa del PDF reordenado...</p>';
      downloadBtn.disabled = true; // Deshabilitar mientras se procesa

      try {
        // Cargar el PDF original usando PDFLib. originalPdfBytes ya es un ArrayBuffer.
        const loadedPdf = await PDFLib.PDFDocument.load(originalPdfBytes);
        const newPdf = await PDFLib.PDFDocument.create(); // Crear un nuevo documento PDF

        // Validar pageOrder antes de usarlo
        const numOriginalPages = loadedPdf.getPageCount();
        const pagesToCopyIndices = pageOrder.map(p => Number(p));

        if (pagesToCopyIndices.some(isNaN)) {
          alert("Error: El orden de las páginas contiene entradas no válidas (NaN).");
          previewContainer.innerHTML = '<p class="status-message" style="color:red;">Error en el orden de las páginas.</p>';
          return;
        }

        if (pagesToCopyIndices.some(idx => idx < 0 || idx >= numOriginalPages)) {
          console.error("Índices de página inválidos:", pagesToCopyIndices, "Páginas originales:", numOriginalPages);
          alert("Error: El orden de las páginas contiene índices fuera del rango permitido.");
          previewContainer.innerHTML = '<p class="status-message" style="color:red;">Error: Índices de página inválidos.</p>';
          return;
        }

        console.log("Usando este orden de páginas para pdf-lib:", pagesToCopyIndices);

        // Copiar las páginas del PDF cargado al nuevo PDF en el orden especificado
        const copiedPages = await newPdf.copyPages(loadedPdf, pagesToCopyIndices);
        copiedPages.forEach(page => {
          newPdf.addPage(page);
        });

        // Guardar el nuevo PDF como un Uint8Array
        reorderedPdfBytes = await newPdf.save();

        console.log('Tamaño del PDF reordenado (Uint8Array):', reorderedPdfBytes.length);
        if (reorderedPdfBytes.length === 0) {
          alert("El archivo PDF generado está vacío. Algo salió mal.");
          previewContainer.innerHTML = '<p class="status-message" style="color:red;">El PDF generado está vacío.</p>';
          return;
        }

        // Generar vista previa de la primera página del PDF reordenado usando pdf.js
        // Es bueno pasar una copia del buffer a pdf.js
        const previewDataArrayBuffer = reorderedPdfBytes.buffer.slice(
          reorderedPdfBytes.byteOffset,
          reorderedPdfBytes.byteOffset + reorderedPdfBytes.byteLength
        );

        const previewPdfDoc = await pdfjsLib.getDocument({ data: previewDataArrayBuffer }).promise;

        if (previewPdfDoc.numPages === 0) {
          previewContainer.innerHTML = '<h3>Vista Previa:</h3><p>El PDF reordenado no tiene páginas.</p>';
          alert("El PDF reordenado no parece tener páginas para la vista previa.");
          return;
        }

        const firstPage = await previewPdfDoc.getPage(1); // pdf.js es 1-indexed
        const viewport = firstPage.getViewport({ scale: 0.4 }); // Escala para la vista previa

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        await firstPage.render({ canvasContext: context, viewport: viewport }).promise;

        previewContainer.innerHTML = '<h3>Vista Previa (primera página):</h3>';
        previewContainer.appendChild(canvas);
        downloadBtn.disabled = false; // Habilitar botón de descarga

      } catch (error) {
        console.error("Error detallado al asignar orden y generar PDF/vista previa:", error);
        alert("Ocurrió un error al reordenar el PDF o generar la vista previa. Revisa la consola para más detalles. Inténtalo de nuevo.");
        previewContainer.innerHTML = `<p class="status-message" style="color:red;">Error al generar vista previa. ${error.message}</p>`;
        reorderedPdfBytes = null; // Invalidar bytes si hubo error
      }



      downloadBtn.disabled = false;
    }

    
    
    
    
    
    window.downloadReorderedPDF = async function downloadReorderedPDF() {
  if (!reorderedPdfBytes) {
    alert("Primero debes reordenar el PDF.");
    return;
  }

  const archivoNombre = archivoCargadoNombre || "PDF_reordenado.pdf";
  const blob = new Blob([reorderedPdfBytes], { type: "application/pdf" });

  // 1️⃣ Descargar al navegador
  const a = document.createElement("a");
  a.href = URL.createObjectURL(blob);
  a.download = archivoNombre;
  a.click();
  URL.revokeObjectURL(a.href);

  // 2️⃣ Guardar en el servidor
  const formData = new FormData();
  formData.append("archivo", blob, archivoNombre);
  formData.append("nombre", archivoNombre);

  try {
    const response = await fetch("guardar_pdf.php", {
      method: "POST",
      body: formData
    });

    const resultado = await response.text();
    console.log("Servidor respondió:", resultado);
  } catch (err) {
    console.error("Error al guardar en el servidor:", err);
  }
}





    
  </script>
</body>

</html>




<style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
      text-align: center;
      margin: 20px;
      background-color: #f4f7f6;
      color: #333;
    }

    h2 {
      color: #2c3e50;
    }

    #drop-zone {
      border: 2px dashed #007bff;
      border-radius: 8px;
      padding: 30px;
      margin-bottom: 20px;
      background-color: #ffffff;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    #drop-zone.dragover {
      background-color: #e9ecef;
    }

    #drop-zone p {
      margin: 0;
      font-size: 1.1em;
      color: #555;
    }

    #pdf-upload {
      display: none;
      /* Hide the default input */
    }

    #thumbnails {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      /* Increased gap */
      justify-content: center;
      padding: 10px;
      margin-top: 20px;
    }

    .page-thumbnail {
      border: 1px solid #ddd;
      /* Softer border */
      border-radius: 8px;
      /* Rounded corners */
      padding: 8px;
      background: #ffffff;
      cursor: grab;
      width: 130px;
      /* Slightly wider */
      text-align: center;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .page-thumbnail:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .page-thumbnail canvas {
      width: 100%;
      height: auto;
      border-radius: 4px;
      /* Rounded corners for canvas */
      border: 1px solid #eee;
    }

    .page-thumbnail div {
      /* Page number label */
      font-size: 0.9em;
      color: #555;
    }

    #preview {
      margin: 30px auto;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background-color: #ffffff;
      max-width: 90%;
      /* Responsive max width */
      width: fit-content;
      /* Fit to content or max width */
    }

    #preview h3 {
      margin-top: 0;
      color: #2c3e50;
    }

    #preview canvas {
      max-width: 100%;
      height: auto;
      border-radius: 4px;
    }


    button, .buttom {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 12px 24px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 1em;
      margin: 10px 5px;
      cursor: pointer;
      border-radius: 6px;
      transition: background-color 0.3s ease, transform 0.1s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    button:hover {
      background-color: #0056b3;
      transform: translateY(-1px);
    }

    button:active {
      transform: translateY(0px);
    }

    button:disabled {
      background-color: #cccccc;
      cursor: not-allowed;
    }


    .spinner {
      border: 4px solid #f3f3f3;
      border-top: 4px solid #007bff;
      /* Match button color */
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
      margin: 20px auto;
      /* Added top/bottom margin */
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .hidden {
      display: none;
    }

    .status-message {
      margin-top: 10px;
      font-style: italic;
      color: #555;
    }
  </style>