document.addEventListener('DOMContentLoaded', function() {
    const notificationListMobile = document.getElementById('notificationListMobile');
    const notificationListDesktop = document.getElementById('notificationListDesktop');
    const notificationButtonMobile = document.getElementById('notificationButtonMobile');
    const notificationButtonDesktop = document.getElementById('notificationButtonDesktop');
    const notificationIcons = document.querySelectorAll('.notification-icon');
    let notifications = [];
    let hasUnreadNotifications = false;

    function fetchNotifications() {
        fetch('/public/includes/notifications.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notifications = data.notifications;
                    hasUnreadNotifications = notifications.some(notif => !notif.is_read);
                    updateNotificationUI();
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    function markNotificationsAsRead() {
        if (hasUnreadNotifications) {
            fetch('/public/includes/notifications.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'mark_as_read' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hasUnreadNotifications = false;
                    notificationIcons.forEach(icon => icon.classList.remove('has-notifications'));
                }
            })
            .catch(error => console.error('Error marking notifications as read:', error));
        }
    }

    // Add click event listeners to mark notifications as read when dropdown is opened
    notificationButtonMobile?.addEventListener('click', markNotificationsAsRead);
    notificationButtonDesktop?.addEventListener('click', markNotificationsAsRead);

    function updateNotificationUI() {
        const notificationLists = [notificationListMobile, notificationListDesktop];
        
        notificationLists.forEach(list => {
            if (!list) return;

            if (notifications.length === 0) {
                list.innerHTML = `
                    <div class="text-center p-3 no-notifications">
                        <i class="fas fa-bell-slash text-muted"></i>
                        <p class="mb-0">No new notifications</p>
                    </div>
                `;
                return;
            }

            // Update notification icons
            if (hasUnreadNotifications) {
                notificationIcons.forEach(icon => icon.classList.add('has-notifications'));
            } else {
                notificationIcons.forEach(icon => icon.classList.remove('has-notifications'));
            }

            list.innerHTML = notifications.map(notification => `
                <div class="notification-item p-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="fas ${getNotificationIcon(notification.type)} me-2"></i>
                        <div>
                            <p class="mb-1">${notification.message}</p>
                            <small class="text-muted">${formatTimestamp(notification.created_at)}</small>
                        </div>
                    </div>
                </div>
            `).join('');
        });
    }

    function getNotificationIcon(type) {
        switch (type) {
            case 'application_approved':
                return 'fa-check-circle text-success';
            case 'application_rejected':
                return 'fa-times-circle text-danger';
            default:
                return 'fa-bell text-primary';
        }
    }

    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleString();
    }

    // Initial fetch
    fetchNotifications();

    // Fetch notifications every 30 seconds
    setInterval(fetchNotifications, 30000);
});
