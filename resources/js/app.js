import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const sidebar = document.querySelector('[data-sidebar]');
const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');

document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        sidebar?.classList.toggle('-translate-x-full');
        sidebarBackdrop?.classList.toggle('hidden');
    });
});

document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

const markerColors = {
    online: '#10b981',
    maintenance: '#f59e0b',
    offline: '#64748b',
};

const statusLabels = {
    online: 'En ligne',
    maintenance: 'Maintenance',
    offline: 'Hors ligne',
};

document.querySelectorAll('[data-camera-map]').forEach((mapElement) => {
    const cameras = JSON.parse(mapElement.dataset.cameras || '[]');
    const map = L.map(mapElement, {
        zoomControl: true,
        scrollWheelZoom: true,
        tap: true,
    }).setView([-11.6647, 27.4794], 12);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    const markers = cameras.map((camera) => {
        const marker = L.circleMarker([camera.latitude, camera.longitude], {
            radius: 9,
            color: '#ffffff',
            weight: 3,
            fillColor: markerColors[camera.status] || markerColors.offline,
            fillOpacity: 1,
        }).addTo(map);

        const popup = document.createElement('div');
        popup.className = 'lynx-map-popup';
        const videoContainer = document.createElement('div');
        videoContainer.className = 'lynx-map-video-container';

        if (camera.preview_url) {
            const video = document.createElement('video');
            video.className = 'lynx-map-video';
            video.src = camera.preview_url;
            video.autoplay = true;
            video.muted = true;
            video.loop = true;
            video.playsInline = true;
            video.preload = 'metadata';
            video.setAttribute('aria-label', `Aperçu vidéo de ${camera.name}`);
            videoContainer.append(video);

            if (camera.preview_is_demo) {
                const demoLabel = document.createElement('span');
                demoLabel.className = 'lynx-map-video-label';
                demoLabel.textContent = 'Démonstration';
                videoContainer.append(demoLabel);
            }

            marker.on('popupopen', () => video.play().catch(() => {}));
            marker.on('popupclose', () => video.pause());
        } else {
            videoContainer.classList.add('lynx-map-video-empty');
            videoContainer.textContent = 'Aucun aperçu vidéo disponible';
        }

        const heading = document.createElement('strong');
        heading.textContent = camera.name;
        const details = document.createElement('span');
        details.textContent = `${camera.code} · ${camera.site}`;
        const status = document.createElement('span');
        status.textContent = `${statusLabels[camera.status] || camera.status} · signal ${camera.last_seen}`;

        popup.append(videoContainer, heading, details, status);
        marker.bindPopup(popup, { closeButton: true, minWidth: 270, maxWidth: 300 });

        return marker;
    });

    const markerGroup = L.featureGroup(markers);
    const resetView = () => {
        if (markers.length === 1) {
            map.setView(markers[0].getLatLng(), 16);
        } else if (markers.length > 1) {
            map.fitBounds(markerGroup.getBounds(), { padding: [45, 45], maxZoom: 16 });
        } else {
            map.setView([-11.6647, 27.4794], 12);
        }
    };

    resetView();

    const wrapper = mapElement.closest('[data-map-wrapper]');
    wrapper?.querySelector('[data-map-reset]')?.addEventListener('click', resetView);
    wrapper?.querySelector('[data-map-fullscreen]')?.addEventListener('click', (event) => {
        wrapper.classList.toggle('lynx-map-fullscreen');
        event.currentTarget.textContent = wrapper.classList.contains('lynx-map-fullscreen') ? 'Réduire' : 'Plein écran';
        window.setTimeout(() => map.invalidateSize(), 180);
    });

    new ResizeObserver(() => map.invalidateSize()).observe(mapElement);
});
