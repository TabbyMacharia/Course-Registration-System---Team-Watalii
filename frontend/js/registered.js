document.addEventListener('DOMContentLoaded', () => {
    let registeredCourses = [];
    let selectedOfferingId = null;

    const dropModal = document.getElementById('drop-modal');
    const cancelDropBtn = document.getElementById('cancel-drop-btn');
    const confirmDropBtn = document.getElementById('confirm-drop-btn');
    const dropCourseTitle = document.getElementById('drop-course-title');
    const searchInput = document.getElementById('registered-search-input');
    const tbody = document.getElementById('registered-table-body');
    const regBadge = document.getElementById('reg-badge');

    if (cancelDropBtn) cancelDropBtn.addEventListener('click', hideDropModal);

    function hideDropModal() {
        dropModal.classList.add('hidden');
        selectedOfferingId = null;
    }

    // Fetch Enrolled Units
    function fetchRegisteredCourses() {
        fetch('../../backend/api/get_registered.php')
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    if (data.message === 'Unauthorized') {
                        window.location.href = 'login.html';
                    }
                    return;
                }

                if (data.student_name) {
                    document.getElementById('user-name-display').textContent = data.student_name;
                    const initials = data.student_name.split(' ').map(n => n[0]).join('').substring(0, 2);
                    document.getElementById('user-avatar').textContent = initials;
                }

                registeredCourses = data.courses;
                regBadge.textContent = registeredCourses.length;
                renderTable(registeredCourses);
            })
            .catch(err => console.error('Error fetching registered courses:', err));
    }

    // Render Table
    function renderTable(courses) {
        tbody.innerHTML = '';

        if (courses.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #888; padding: 20px;">No registered courses found.</td></tr>';
            return;
        }

        courses.forEach(c => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td><strong>${c.course_code}</strong></td>
                <td>${c.course_name}</td>
                <td>${c.credit_hours}</td>
                <td>${c.instructor}</td>
                <td>${c.class_room || 'TBA'}</td>
                <td>
                    <button class="btn btn-danger btn-sm btn-drop">Drop</button>
                </td>
            `;

            tr.querySelector('.btn-drop').addEventListener('click', () => {
                selectedOfferingId = c.offering_id;
                dropCourseTitle.textContent = `${c.course_code}: ${c.course_name}`;
                dropModal.classList.remove('hidden');
            });

            tbody.appendChild(tr);
        });
    }

    // Confirm Drop Action
    if (confirmDropBtn) {
        confirmDropBtn.addEventListener('click', () => {
            if (!selectedOfferingId) return;

            const formData = new FormData();
            formData.append('offering_id', selectedOfferingId);

            fetch('../../backend/api/drop_course.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                hideDropModal();
                if (data.success) {
                    fetchRegisteredCourses();
                }
            })
            .catch(err => console.error('Error dropping course:', err));
        });
    }

    // Search
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            const filtered = registeredCourses.filter(c => 
                c.course_name.toLowerCase().includes(query) ||
                c.course_code.toLowerCase().includes(query) ||
                c.instructor.toLowerCase().includes(query)
            );
            renderTable(filtered);
        });
    }

    fetchRegisteredCourses();
});