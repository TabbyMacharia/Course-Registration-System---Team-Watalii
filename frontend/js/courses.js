// frontend/js/courses.js

document.addEventListener('DOMContentLoaded', () => {
    loadAvailableCourses();
});

function loadAvailableCourses() {
    const courseGrid = document.getElementById('course-grid');

    fetch('../../backend/api/get_courses.php')
        .then(res => res.json())
        .then(data => {
            if (!data.success || !data.courses || data.courses.length === 0) {
                courseGrid.innerHTML = '<p style="color: var(--text-muted);">No available courses found.</p>';
                return;
            }

            courseGrid.innerHTML = data.courses.map(course => `
                <div class="course-card">
                    <div class="card-header">
                        <span class="course-code">${escapeHtml(course.course_code)}</span>
                    </div>
                    <h3 class="course-title">${escapeHtml(course.course_name)}</h3>
                    <p class="course-meta">${escapeHtml(course.description || 'No description provided.')}</p>
                    <p class="course-meta"><strong>Room:</strong> ${escapeHtml(course.class_room || 'TBA')}</p>
                    <div class="card-actions" style="margin-top: 16px;">
                        <button class="btn btn-primary" onclick="registerForCourse(${course.offering_id})">
                            REGISTER
                        </button>
                    </div>
                </div>
            `).join('');
        })
        .catch(err => {
            console.error('Error fetching courses:', err);
            courseGrid.innerHTML = '<p style="color: var(--danger);">Failed to load courses.</p>';
        });
}

function registerForCourse(offeringId) {
    if (!offeringId) {
        alert('Invalid course selected.');
        return;
    }

    const formData = new FormData();
    formData.append('offering_id', offeringId);

    fetch('../../backend/api/register_course.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            // Refresh available courses or redirect to registered courses page
            loadAvailableCourses();
        }
    })
    .catch(err => {
        console.error('Registration error:', err);
        alert('An error occurred while registering for the course.');
    });
}

function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, m => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    }[m]));
}