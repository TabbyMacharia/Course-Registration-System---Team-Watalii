document.addEventListener('DOMContentLoaded', () => {
    let allCourses = [];

    const detailsModal = document.getElementById('details-modal');
    const closeModalX = document.getElementById('close-modal-x');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const searchInput = document.getElementById('course-search-input');

    if (closeModalX) closeModalX.addEventListener('click', hideModal);
    if (closeModalBtn) closeModalBtn.addEventListener('click', hideModal);

    function hideModal() {
        detailsModal.classList.add('hidden');
    }

    // Fetch Available Courses
    function fetchAvailableCourses() {
        fetch('../../backend/api/get_courses.php')
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

                allCourses = data.courses;
                renderCourseGrid(allCourses);
                updateRegisteredBadge();
            })
            .catch(err => console.error('Error fetching courses:', err));
    }

    // Render Grid Cards
    function renderCourseGrid(courses) {
        const grid = document.getElementById('available-courses-grid');
        grid.innerHTML = '';

        if (courses.length === 0) {
            grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #888;">No available courses found.</p>';
            return;
        }

        courses.forEach(c => {
            const isReg = parseInt(c.is_registered) === 1;
            const card = document.createElement('div');
            card.className = 'course-card';

            card.innerHTML = `
                <div class="card-header">
                    <span class="badge">${c.course_code}</span>
                    <span class="credits" style="font-size: 12px; color: #666;">${c.credit_hours} Credits</span>
                </div>
                <h3>${c.course_name}</h3>
                <p class="instructor"><i class="fa-solid fa-chalkboard-user"></i> ${c.instructor}</p>
                <p class="room" style="font-size: 12px; color: #777; margin-bottom: 12px;"><i class="fa-solid fa-location-dot"></i> ${c.class_room || 'TBA'}</p>
                
                <div class="card-actions" style="display: flex; gap: 8px;">
                    <button class="btn btn-outline btn-details">Details</button>
                    ${isReg 
                        ? `<button class="btn btn-disabled" disabled>Registered</button>` 
                        : `<button class="btn btn-primary btn-register">Register</button>`
                    }
                </div>
            `;

            card.querySelector('.btn-details').addEventListener('click', () => {
                document.getElementById('modal-title').textContent = c.course_name;
                document.getElementById('modal-code').textContent = c.course_code;
                document.getElementById('modal-credits').textContent = c.credit_hours;
                document.getElementById('modal-instructor').textContent = c.instructor;
                document.getElementById('modal-room').textContent = c.class_room || 'TBA';
                document.getElementById('modal-description').textContent = c.description || 'No description provided.';
                detailsModal.classList.remove('hidden');
            });

            const regBtn = card.querySelector('.btn-register');
            if (regBtn) {
                regBtn.addEventListener('click', () => registerForCourse(c.offering_id));
            }

            grid.appendChild(card);
        });
    }

    // Register Action
    function registerForCourse(offeringId) {
        const formData = new FormData();
        formData.append('offering_id', offeringId);

        fetch('../../backend/api/register_courses.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    fetchAvailableCourses();
                }
            })
            .catch(err => console.error('Error registering:', err));
    }

    // Update Badge
    function updateRegisteredBadge() {
        fetch('../../backend/api/get_registered.php')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('reg-badge').textContent = data.courses.length;
                }
            });
    }

    // Search
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            const filtered = allCourses.filter(c => 
                c.course_name.toLowerCase().includes(query) ||
                c.course_code.toLowerCase().includes(query) ||
                c.instructor.toLowerCase().includes(query)
            );
            renderCourseGrid(filtered);
        });
    }

    fetchAvailableCourses();
});