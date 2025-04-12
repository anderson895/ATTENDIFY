document.querySelectorAll(".edit").forEach((button) => {
    button.addEventListener("click", (e) => {
        const id = button.dataset.id;
        const name = button.dataset.name;

        if (name === "course") {
            document.querySelector("#editCourseID").value = id;
            document.querySelector("#editCourseForm").style.display = "block";

            // Fetch existing data to populate the form
            fetch(`/fetchCourseData.php?id=${id}`)
                .then((response) => response.json())
                .then((data) => {
                    document.querySelector("#editCourseName").value = data.name;
                    document.querySelector("#editCourseCode").value = data.courseCode;
                    document.querySelector("#editYearLevel").value = data.yearlevelID;
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

// Close the form when the close button is clicked
document.querySelectorAll(".formDiv .close").forEach((button) => {
    button.addEventListener("click", () => {
        button.closest(".formDiv").style.display = "none";
    });
});
