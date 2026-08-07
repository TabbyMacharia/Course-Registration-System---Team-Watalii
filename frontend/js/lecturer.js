document.addEventListener('DOMContentLoaded', () => {
    const addCourseForm = document.getElementById('add-course-form');

    if (addCourseForm) {
        addCourseForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(addCourseForm);

            fetch('../../backend/api/add_course_offering.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    addCourseForm.reset();
                }
            })
            .catch(err => {
                console.error('Error publishing course:', err);
                alert('Failed to publish course. Please try again.');
            });
        });
    }
});