document.querySelectorAll(".edit").forEach((button) => {
    button.addEventListener("click", (e) => {
        const id = button.dataset.id;
        const name = button.dataset.name;

        if (name === "unit") {
            document.querySelector("#editUnitID").value = id;
            document.querySelector("#editUnitForm").style.display = "block";

            // Fetch existing data to populate the form
            fetch(`/fetchUnitData.php?id=${id}`)
                .then((response) => response.json())
                .then((data) => {
                    document.querySelector("#editUnitName").value = data.name;
                    document.querySelector("#editUnitCode").value = data.unitCode;
                    document.querySelector("#editCourse").value = data.courseID;
                });
        } else if (name === "unit") {
            document.querySelector("#editUnitID").value = id;
            document.querySelector("#editUnitForm").style.display = "block";

            // Fetch existing data to populate the form
            fetch(`/fetchUnitData.php?id=${id}`)
                .then((response) => response.json())
                .then((data) => {
                    document.querySelector("#editUnitName").value = data.name;
                    document.querySelector("#editUnitCode").value = data.unitCode;
                    document.querySelector("#editCourse").value = data.courseID;
                });
        } else if (name === "yearlevel") {
            document.querySelector("#editYearLevelID").value = id;
            document.querySelector("#editYearLevelForm").style.display = "block";

            // Fetch existing data to populate the form
            fetch(`/fetchYearLevelData.php?id=${id}`)
                .then((response) => response.json())
                .then((data) => {
                    document.querySelector("#editYearLevelName").value = data.name;
                    document.querySelector("#editYearLevelCode").value = data.yearLevelCode;
                });
        }
    });
});
document.querySelector('.edit').addEventListener('click', function() {
    const id = this.dataset.id;
    // Fetch subject details and populate fields
    document.getElementById('editScheduleDay').value = data.scheduleDay;
    document.getElementById('editStartTime').value = data.startTime;
    document.getElementById('editEndTime').value = data.endTime;
});

// Close the form when the close button is clicked
document.querySelectorAll(".formDiv .close").forEach((button) => {
    button.addEventListener("click", () => {
        button.closest(".formDiv").style.display = "none";
    });
});
