var labels = [];
let detectedFaces = [];
let sendingData = false;

function updateTable() {

  
  var selectedCourseID = document.getElementById("courseSelect").value;
  var selectedUnitCode = document.getElementById("unitSelect").value;
  var selectedroom = document.getElementById("roomSelect").value;


  console.log(selectedroom);


  var xhr = new XMLHttpRequest();
  xhr.open("POST", "resources/pages/depthead/manageFolder.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);

        

      if (response.status === "success") {
        labels = response.data;

        if (selectedCourseID && selectedUnitCode && selectedroom) {
          updateOtherElements();
        }
        document.getElementById("professorTableContainer").innerHTML =
          response.html;
      } else {
        document.getElementById("professorTableContainer").innerHTML =
        response.html;
        console.error("Error:", response.message);
        
      }
    }
  };
  xhr.send(
    "courseID=" +
      encodeURIComponent(selectedCourseID) +
      "&unitID=" +
      encodeURIComponent(selectedUnitCode) +
      "&roomID=" +
      encodeURIComponent(selectedroom)
  );
}

function markAttendance(detectedFaces) {
  document.querySelectorAll("#professorTableContainer tr").forEach((row) => {
    const registrationNumber = row.cells[0].innerText.trim();

    if (detectedFaces.includes(registrationNumber)) {
      const now = new Date();
      const timestamp = now.toLocaleString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
      });

      const timeInCell = row.cells[5];    // 6th column
      const timeOutCell = row.cells[6];   // 7th column
      const statusCell = row.cells[7];    // 8th column

      const timeInAttr = timeInCell.getAttribute('data-timein');
      console.log(timeInAttr);

      if (!timeInAttr || timeInAttr === "") {
        console.log(timeInCell.textContent.trim());
        console.log('For timeIn');
        timeInCell.innerHTML = createAttendanceMarkup(timestamp, true);
     
        statusCell.textContent = "";
      } else {
        console.log('For timeOut');
        timeOutCell.innerHTML = createAttendanceMarkup(timestamp, true);
        statusCell.textContent = "";
      }
    }
  });
}


function createAttendanceMarkup(timestamp, isTimeIn) {
  const formattedTimestamp = timestamp.replace(/([AP]M)/, '<strong>$1</strong>');
  
  console.log(isTimeIn);

  return `
    <div class="attendance-mark ${isTimeIn ? 'time-in' : 'time-out'}">
      <span class="time">${formattedTimestamp}</span>
      <span class="check">${isTimeIn ? '→' : '←'}</span>
    </div>
  `;
}

function saveAttendanceRecord(registrationNumber, timestamp, type) {
  fetch('save_attendance.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      registrationNumber: registrationNumber,
      timestamp: timestamp,
      type: type
    })
  })
  .then(response => response.json())
  .then(data => {

    console.log(response);

    if (data.success) {
      showNotification(`Successfully marked ${type.replace('_', ' ')}`, 'success');
    } else {
      showNotification('Failed to save attendance', 'error');
    }
  })
  .catch(error => {
    console.error('Error saving attendance:', error);
    showNotification('Error saving attendance', 'error');
  });
}

// Add notification function
function showNotification(message, type) {
  const notification = document.createElement('div');
  notification.className = `alert alert-${type} notification`;
  notification.textContent = message;
  document.body.appendChild(notification);
  setTimeout(() => notification.remove(), 3000);
}

function updateOtherElements() {
  const video = document.getElementById("video");
  const videoContainer = document.querySelector(".video-container");
  const startButton = document.getElementById("startButton");
  let webcamStarted = false;
  let modelsLoaded = false;

  Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
    faceapi.nets.faceRecognitionNet.loadFromUri("models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("models"),
  ])
    .then(() => {
      modelsLoaded = true;
      console.log("models loaded successfully");
    })
    .catch(() => {
      alert("models not loaded, please check your model folder location");
    });
  startButton.addEventListener("click", async () => {
    videoContainer.style.display = "flex";
    if (!webcamStarted && modelsLoaded) {
      startWebcam();
      webcamStarted = true;
    }
  });

  function startWebcam() {
    navigator.mediaDevices
      .getUserMedia({
        video: true,
        audio: false,
      })
      .then((stream) => {
        video.srcObject = stream;
        videoStream = stream;
      })
      .catch((error) => {
        console.error(error);
      });
  }
  async function getLabeledFaceDescriptions() {
    const labeledDescriptors = [];

    for (const label of labels) {
      const descriptions = [];

      for (let i = 1; i <= 5; i++) {
        try {
          const img = await faceapi.fetchImage(
            `resources/labels/${label}/${i}.png`
          );
          const detections = await faceapi
            .detectSingleFace(img)
            .withFaceLandmarks()
            .withFaceDescriptor();

          if (detections) {
            descriptions.push(detections.descriptor);
          } else {
            console.log(`No face detected in ${label}/${i}.png`);
          }
        } catch (error) {
          console.error(`Error processing ${label}/${i}.png:`, error);
        }
      }

      if (descriptions.length > 0) {
        detectedFaces.push(label);
        labeledDescriptors.push(
          new faceapi.LabeledFaceDescriptors(label, descriptions)
        );
      }
    }

    return labeledDescriptors;
  }

  video.addEventListener("play", async () => {
    const labeledFaceDescriptors = await getLabeledFaceDescriptions();
    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

    const canvas = faceapi.createCanvasFromMedia(video);
    videoContainer.appendChild(canvas);

    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {
      const detections = await faceapi
        .detectAllFaces(video)
        .withFaceLandmarks()
        .withFaceDescriptors();

      const resizedDetections = faceapi.resizeResults(detections, displaySize);

      canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

      const results = resizedDetections.map((d) => {
        return faceMatcher.findBestMatch(d.descriptor);
      });
      detectedFaces = results.map((result) => result.label);
      markAttendance(detectedFaces);

      results.forEach((result, i) => {
        const box = resizedDetections[i].detection.box;
        const drawBox = new faceapi.draw.DrawBox(box, {
          label: result,
        });
        drawBox.draw(canvas);
      });
    }, 100);
  });
}

function sendAttendanceDataToServer() {
  const attendanceData = [];

  document
    .querySelectorAll("#professorTableContainer tr")
    .forEach((row, index) => {
      if (index === 0) return;
      const professorID = row.cells[0].innerText.trim();
      const course = row.cells[2].innerText.trim();
      const unit = row.cells[3].innerText.trim();
      const attendance_time = row.cells[5].innerText.trim();

      console.log(attendance_time)

      attendanceData.push({ professorID, course, unit, attendance_time });
    });

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "handle_attendance", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);

          if (response.status === "success") {
            showMessage(
              response.message || "Attendance recorded successfully."
            );
            
            setTimeout(function () {
              location.reload();
            }, 2000); // 2000 milliseconds = 2 seconds
            
          } else {
            showMessage(
              response.message ||
                "An error occurred while recording attendance."
            );
          }
        } catch (e) {
          showMessage("Error: Failed to parse the response from the server.");
          console.error(e);
        }
      } else {
        showMessage(
          "Error: Unable to record attendance. HTTP Status: " + xhr.status
        );
        console.error("HTTP Error", xhr.status, xhr.statusText);
      }
    }
  };

  xhr.send(JSON.stringify(attendanceData));
}
function showMessage(message) {
  var messageDiv = document.getElementById("messageDiv");
  messageDiv.style.display = "block";
  messageDiv.innerHTML = message;
  console.log(message);
  messageDiv.style.opacity = 1;
  setTimeout(function () {
    messageDiv.style.opacity = 0;
  }, 5000);
}
function stopWebcam() {
  if (videoStream) {
    const tracks = videoStream.getTracks();

    tracks.forEach((track) => {
      track.stop();
    });

    video.srcObject = null;
    videoStream = null;
  }
}

document.getElementById("endAttendance").addEventListener("click", function () {
  sendAttendanceDataToServer();
  const videoContainer = document.querySelector(".video-container");
  videoContainer.style.display = "none";
  stopWebcam();
});

// Add these styles to your existing CSS
const styles = `
  .attendance-mark {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .time {
    font-family: monospace;
    color: #333;
  }

  .check {
    color: #28a745;
    font-weight: bold;
  }

  .marked {
    animation: highlight 0.5s ease;
  }

  @keyframes highlight {
    0% { background-color: rgba(141, 28, 28, 0.1); }
    100% { background-color: transparent; }
  }
`;

// Add the styles to the document
const styleSheet = document.createElement("style");
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);

// Add these additional styles to make the AM/PM more visible
const additionalStyles = `
  .time strong {
    color: #8d1c1c;
    font-size: 0.9em;
    margin-left: 2px;
    font-weight: 700;
  }

  .attendance-mark {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
  }

  .time {
    font-family: 'Arial', sans-serif;
    color: #333;
    font-weight: 500;
  }

  .check {
    color: #8d1c1c;
    font-weight: bold;
    font-size: 16px;
  }
`;

// Add the additional styles to the document
const styleSheetAdditional = document.createElement("style");
styleSheetAdditional.textContent = additionalStyles;
document.head.appendChild(styleSheetAdditional);
