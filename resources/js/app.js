const chatForm = document.querySelector('[data-chat-form]');

if (chatForm) {
    const chatInput = chatForm.querySelector('input[name="message"]');
    const chatLog = document.querySelector('#chat-messages');
    const sendButton = chatForm.querySelector('button[type="submit"]');
    const conversation = [];

    const addMessage = (role, content, extraClass = '') => {
        const message = document.createElement('div');
        message.className = role === 'user' ? 'preview-prompt' : 'preview-answer';

        if (extraClass) {
            message.classList.add(extraClass);
        }

        if (role !== 'user') {
            const icon = document.createElement('span');
            icon.className = 'answer-star';
            icon.textContent = '✳';
            message.append(icon);
        }

        const text = document.createElement('span');
        text.textContent = content;
        message.append(text);
        chatLog.append(message);
        chatLog.scrollTop = chatLog.scrollHeight;

        return message;
    };

    const sendMessage = async (content) => {
        const question = content.trim();

        if (!question || sendButton.disabled) {
            return;
        }

        const messages = [...conversation.slice(-8), { role: 'user', content: question }];
        addMessage('user', question);
        chatInput.value = '';
        chatInput.disabled = true;
        sendButton.disabled = true;
        const pendingMessage = addMessage('assistant', 'Thinking…');

        try {
            const response = await fetch(chatForm.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ messages }),
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'The assistant could not respond right now. Please try again.');
            }

            const reply = result.reply;
            pendingMessage.querySelector('span:last-child').textContent = reply;
            conversation.splice(0, conversation.length, ...messages, { role: 'assistant', content: reply });
        } catch (error) {
            pendingMessage.classList.add('preview-error');
            pendingMessage.querySelector('span:last-child').textContent = error.message || 'The assistant could not respond right now. Please try again.';
        } finally {
            chatInput.disabled = false;
            sendButton.disabled = false;
            chatInput.focus();
        }
    };

    chatForm.addEventListener('submit', (event) => {
        event.preventDefault();
        sendMessage(chatInput.value);
    });

    document.querySelectorAll('[data-ask]').forEach((suggestion) => {
        suggestion.addEventListener('click', () => {
            sendMessage(suggestion.dataset.ask);
        });
    });
}

const wardMaps = document.querySelectorAll('[data-ward-map]');

if (wardMaps.length > 0) {
    const svgNamespace = 'http://www.w3.org/2000/svg';
    const themeStyles = getComputedStyle(document.documentElement);
    const otherWardFill = themeStyles.getPropertyValue('--ward-other').trim() || '#c4e5d1';
    const shaniWardFill = themeStyles.getPropertyValue('--green').trim() || '#008753';

    const createSvgElement = (tagName, attributes = {}) => {
        const element = document.createElementNS(svgNamespace, tagName);

        Object.entries(attributes).forEach(([name, value]) => {
            element.setAttribute(name, value);
        });

        return element;
    };

    const getOuterRings = (feature) => {
        if (feature.geometry?.type === 'Polygon') {
            return feature.geometry.coordinates.slice(0, 1);
        }

        if (feature.geometry?.type === 'MultiPolygon') {
            return feature.geometry.coordinates.map((polygon) => polygon[0]).filter(Boolean);
        }

        return [];
    };

    const getScaleDistance = (pixelsPerDegree, maximumWidth) => {
        const maximumDistance = maximumWidth * 111.32 / pixelsPerDegree;
        const magnitude = 10 ** Math.floor(Math.log10(maximumDistance));
        const normalizedDistance = maximumDistance / magnitude;
        const niceDistance = normalizedDistance >= 5 ? 5 : normalizedDistance >= 2 ? 2 : 1;

        return niceDistance * magnitude;
    };

    const addNorthArrow = (mapSvg) => {
        const compass = createSvgElement('g', { class: 'map-compass', transform: 'translate(579 74)' });
        const north = createSvgElement('path', { d: 'M0 18 7 0 14 18 7 14Z', class: 'map-compass-arrow' });
        const label = createSvgElement('text', { x: '7', y: '30', class: 'map-compass-label' });

        label.textContent = 'N';
        compass.append(north, label);
        mapSvg.append(compass);
    };

    const addScaleBar = (mapSvg, scale) => {
        const distanceKm = getScaleDistance(scale, 100);
        const barWidth = distanceKm * scale / 111.32;
        const x = 579 - barWidth;
        const y = 304;
        const bar = createSvgElement('g', { class: 'map-scale-bar' });
        const line = createSvgElement('line', { x1: x, y1: y, x2: x + barWidth, y2: y });
        const leftTick = createSvgElement('line', { x1: x, y1: y - 4, x2: x, y2: y + 4 });
        const rightTick = createSvgElement('line', { x1: x + barWidth, y1: y - 4, x2: x + barWidth, y2: y + 4 });
        const label = createSvgElement('text', { x: x + barWidth / 2, y: y - 7, class: 'map-scale-label' });

        label.textContent = `${distanceKm} km`;
        bar.append(line, leftTick, rightTick, label);
        mapSvg.append(bar);
    };

    const normalizeWardName = (name) => (name ?? '').trim().toLowerCase().replace(/\s*\/\s*/g, '-').replace(/\s+ward$/, '').replace(/[^a-z0-9-]/g, '');

    const getFeaturePoint = (feature) => {
        if (feature.geometry?.type === 'Point' && Array.isArray(feature.geometry.coordinates)) {
            return feature.geometry.coordinates;
        }

        const latitude = Number(feature.latitude ?? feature.lat);
        const longitude = Number(feature.longitude ?? feature.lon);

        return Number.isFinite(latitude) && Number.isFinite(longitude) ? [longitude, latitude] : null;
    };

    const isPointInsideRing = ([longitude, latitude], ring) => {
        let isInside = false;

        for (let index = 0, previousIndex = ring.length - 1; index < ring.length; previousIndex = index, index += 1) {
            const [currentLongitude, currentLatitude] = ring[index];
            const [previousLongitude, previousLatitude] = ring[previousIndex];
            const crossesLatitude = (currentLatitude > latitude) !== (previousLatitude > latitude);
            const crossingLongitude = ((previousLongitude - currentLongitude) * (latitude - currentLatitude)
                / (previousLatitude - currentLatitude)) + currentLongitude;

            if (crossesLatitude && longitude < crossingLongitude) {
                isInside = !isInside;
            }
        }

        return isInside;
    };

    const findContainingWard = (point, hint, wards) => {
        const normalizedHint = normalizeWardName(hint);
        const hintedWard = wards.find((ward) => normalizeWardName(ward.name) === normalizedHint);

        if (hintedWard) {
            return hintedWard;
        }

        return wards.find((ward) => ward.rings.some((ring) => isPointInsideRing(point, ring))) ?? null;
    };

    const renderWardMap = (wardMap, data) => {
        const features = data.boundaries ?? [];
        const assets = {
            homes: data.homes ?? [],
            schools: data.schools ?? [],
            health: data.health ?? [],
        };
        const unavailable = data.unavailable ?? [];
        const updatedAt = data.fetched_at ? new Date(data.fetched_at) : null;
        const updatedNote = updatedAt && ! Number.isNaN(updatedAt.getTime()) ? ` · data updated ${updatedAt.toLocaleDateString()}` : '';
        const mapSvg = wardMap.querySelector('.ward-map-svg');
        const mapStatus = wardMap.querySelector('[data-map-status]');
        const selectionStatus = wardMap.querySelector('[data-map-selected]');
        const googleMapsLink = wardMap.querySelector('[data-google-maps-link]');
        const wards = features.map((feature) => ({
            feature,
            rings: getOuterRings(feature),
            attributes: feature.properties ?? feature.attributes ?? {},
        })).filter((ward) => ward.rings.length > 0).sort((first, second) => {
            const firstName = first.attributes.wardname ?? first.attributes.WARDNAME ?? '';
            const secondName = second.attributes.wardname ?? second.attributes.WARDNAME ?? '';

            return firstName.localeCompare(secondName);
        });

        if (wards.length === 0) {
            throw new Error('No Shani ward boundaries were returned.');
        }

        const points = wards.flatMap((ward) => ward.rings.flat());
        const longitudes = points.map(([longitude]) => longitude);
        const latitudes = points.map(([, latitude]) => latitude);
        const minLongitude = Math.min(...longitudes);
        const maxLongitude = Math.max(...longitudes);
        const minLatitude = Math.min(...latitudes);
        const maxLatitude = Math.max(...latitudes);
        const meanLatitude = (minLatitude + maxLatitude) / 2;
        const longitudeScale = Math.cos(meanLatitude * Math.PI / 180);
        const project = ([longitude, latitude]) => [
            (longitude - minLongitude) * longitudeScale,
            maxLatitude - latitude,
        ];
        const projectedPoints = points.map(project);
        const projectedWidth = Math.max(...projectedPoints.map(([x]) => x));
        const projectedHeight = Math.max(...projectedPoints.map(([, y]) => y));
        const mapArea = { left: 43, top: 70, width: 534, height: 210 };
        const scale = Math.min(mapArea.width / projectedWidth, mapArea.height / projectedHeight);
        const drawnWidth = projectedWidth * scale;
        const drawnHeight = projectedHeight * scale;
        const offsetX = mapArea.left + (mapArea.width - drawnWidth) / 2;
        const offsetY = mapArea.top + (mapArea.height - drawnHeight) / 2;
        const toMapPoint = (point) => {
            const [x, y] = project(point);

            return [offsetX + x * scale, offsetY + y * scale];
        };
        const satelliteBackground = createSvgElement('image', {
            class: 'ward-map-background',
            x: offsetX,
            y: offsetY,
            width: drawnWidth,
            height: drawnHeight,
            preserveAspectRatio: 'none',
            opacity: '0.78',
            'aria-hidden': 'true',
        });
        const satelliteQuery = new URLSearchParams({
            bbox: `${minLongitude},${minLatitude},${maxLongitude},${maxLatitude}`,
            bboxSR: '4326',
            imageSR: '4326',
            size: `${Math.ceil(drawnWidth * 2)},${Math.ceil(drawnHeight * 2)}`,
            format: 'jpgpng',
            transparent: 'false',
            f: 'image',
        });
        satelliteBackground.setAttribute('href', `https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/export?${satelliteQuery}`);
        const shapeGroup = createSvgElement('g', { class: 'ward-shapes' });
        const markerGroup = createSvgElement('g', { class: 'ward-map-markers' });
        const wardCounts = new Map(wards.map((ward) => [ward.attributes.wardname ?? ward.attributes.WARDNAME ?? 'Ward', { homes: 0, schools: 0, health: 0 }]));
        const requestedWardSlug = new URLSearchParams(window.location.search).get('ward');
        const selectedWardFromUrl = wards.find((ward) => normalizeWardName(ward.attributes.wardname ?? ward.attributes.WARDNAME) === normalizeWardName(requestedWardSlug));
        let selectedWardName = selectedWardFromUrl?.attributes.wardname ?? selectedWardFromUrl?.attributes.WARDNAME ?? 'Shani';

        const selectWard = (wardName, detail = null, selectedCoordinate = null) => {
            shapeGroup.querySelectorAll('.ward-polygon').forEach((shape) => {
                shape.classList.remove('is-selected');
                shape.setAttribute('aria-pressed', 'false');
            });

            const polygon = [...shapeGroup.querySelectorAll('.ward-polygon')].find((shape) => shape.dataset.wardName === wardName);

            if (polygon) {
                polygon.classList.add('is-selected');
                polygon.setAttribute('aria-pressed', 'true');
            }

            selectedWardName = wardName;
            const counts = wardCounts.get(wardName) ?? { homes: 0, schools: 0, health: 0 };
            const summary = `${wardName} Ward · ${counts.homes} mapped homes · ${counts.schools} schools · ${counts.health} primary health ${counts.health === 1 ? 'facility' : 'facilities'}`;
            const unavailableNote = unavailable.length > 0 ? ` · ${unavailable.join(', ')} unavailable` : '';
            mapStatus.textContent = detail ? `${detail} · ${wardName} Ward` : `${summary}${unavailableNote}${updatedNote}`;

            const selectedWard = wards.find((ward) => (ward.attributes.wardname ?? ward.attributes.WARDNAME ?? 'Ward') === wardName);
            const wardPoints = selectedWard?.rings.flat() ?? [];
            const [longitude, latitude] = selectedCoordinate ?? (wardPoints.length > 0
                ? wardPoints.reduce(([longitudeSum, latitudeSum], [pointLongitude, pointLatitude]) => [longitudeSum + pointLongitude, latitudeSum + pointLatitude], [0, 0]).map((value) => value / wardPoints.length)
                : [11.953, 10.193]);
            const googleMapsQuery = new URLSearchParams({
                api: '1',
                query: `${latitude.toFixed(6)},${longitude.toFixed(6)}`,
            });

            googleMapsLink.href = `https://www.google.com/maps/search/?${googleMapsQuery}`;
            googleMapsLink.setAttribute('aria-label', `Open ${detail ?? `${wardName} Ward`}, Shani, Borno in Google Maps`);

            if (selectionStatus) {
                selectionStatus.textContent = detail ? `${detail} is in ${wardName} Ward. ${summary}${unavailableNote}` : `${summary}${unavailableNote}. Open this location in Google Maps.`;
            }
        };

        const addPointMarker = (item, kind, wardName) => {
            const point = getFeaturePoint(item);

            if (!point || !wardCounts.has(wardName)) {
                return;
            }

            const [x, y] = toMapPoint(point);
            const properties = item.properties ?? item.attributes ?? item.tags ?? {};
            const label = properties.name ?? properties.facility_n ?? properties.facility_name ?? properties['addr:housename'] ?? '';
            const humanLabel = kind === 'home' ? (label || 'Mapped residential building') : (label || (kind === 'school' ? 'Unnamed school' : 'Unnamed primary health facility'));
            const markerClass = kind === 'home' ? 'map-home' : `map-${kind}-marker`;
            const markerAttributes = {
                class: markerClass,
                tabindex: '0',
                role: 'button',
                'aria-label': `${humanLabel}, ${wardName} Ward`,
            };

            if (kind === 'home') {
                Object.assign(markerAttributes, {
                    x: (x - 1.5).toFixed(2),
                    y: (y - 1.5).toFixed(2),
                    width: '3',
                    height: '3',
                });
            } else {
                markerAttributes.transform = `translate(${x.toFixed(2)} ${y.toFixed(2)})`;
            }

            const marker = createSvgElement(kind === 'home' ? 'rect' : 'g', markerAttributes);
            const title = createSvgElement('title');
            title.textContent = `${humanLabel} · ${wardName} Ward`;
            marker.append(title);

            if (kind !== 'home') {
                const circle = createSvgElement('circle', { r: '6' });
                const letter = createSvgElement('text', { x: '0', y: '0', class: 'map-facility-letter' });
                letter.textContent = kind === 'school' ? 'S' : '+';
                marker.append(circle, letter);
            }

            const activateMarker = () => selectWard(wardName, humanLabel, point);
            marker.addEventListener('click', activateMarker);
            marker.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    activateMarker();
                }
            });
            markerGroup.append(marker);
        };

        const addMappedItems = (items, kind, wardHintFor) => {
            items.forEach((item, index) => {
                const point = getFeaturePoint(item);

                if (!point) {
                    return;
                }

                const ward = findContainingWard(point, wardHintFor(item), wards);

                if (!ward) {
                    return;
                }

                const wardName = ward.attributes.wardname ?? ward.attributes.WARDNAME ?? 'Ward';
                wardCounts.get(wardName)[kind === 'home' ? 'homes' : kind === 'school' ? 'schools' : 'health'] += 1;
                addPointMarker(item, kind, wardName);
            });
        };

        addMappedItems(assets.homes, 'home', () => '');
        addMappedItems(assets.schools, 'school', (item) => (item.properties ?? {}).wardname);
        addMappedItems(assets.health, 'health', (item) => (item.properties ?? {}).ward);

        wards.forEach((ward) => {
            const wardName = ward.attributes.wardname ?? ward.attributes.WARDNAME ?? 'Ward';
            const isShaniWard = wardName.trim().toLowerCase().replace(/\s+ward$/, '') === 'shani';
            const allWardPoints = ward.rings.flat();
            const averageLongitude = allWardPoints.reduce((sum, [longitude]) => sum + longitude, 0) / allWardPoints.length;
            const averageLatitude = allWardPoints.reduce((sum, [, latitude]) => sum + latitude, 0) / allWardPoints.length;
            const labelPoint = toMapPoint([averageLongitude, averageLatitude]);
            const pathData = ward.rings.map((ring) => ring.map((point, pointIndex) => {
                const [x, y] = toMapPoint(point);

                return `${pointIndex === 0 ? 'M' : 'L'}${x.toFixed(2)},${y.toFixed(2)}`;
            }).join(' ') + ' Z').join(' ');
            const polygon = createSvgElement('path', {
                d: pathData,
                class: `ward-polygon${isShaniWard ? ' is-shani-ward is-selected' : ''}`,
                fill: isShaniWard ? shaniWardFill : otherWardFill,
                'fill-opacity': isShaniWard ? '0.62' : '0.5',
                tabindex: '0',
                role: 'button',
                'aria-label': `Select ${wardName} ward`,
                'aria-pressed': isShaniWard ? 'true' : 'false',
            });
            const label = createSvgElement('text', {
                x: labelPoint[0].toFixed(2),
                y: labelPoint[1].toFixed(2),
                class: `ward-label${isShaniWard ? ' is-shani-label' : ''}`,
            });

            label.textContent = wardName;

            polygon.dataset.wardName = wardName;
            const activateWard = () => selectWard(wardName);

            polygon.addEventListener('click', activateWard);
            polygon.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    activateWard();
                }
            });
            shapeGroup.append(polygon, label);
        });

        mapSvg.querySelectorAll('.ward-map-background, .ward-shapes, .ward-map-markers, .map-compass, .map-scale-bar').forEach((element) => element.remove());
        mapSvg.append(satelliteBackground, shapeGroup, markerGroup);
        addNorthArrow(mapSvg);
        addScaleBar(mapSvg, scale);
        const totalHomes = [...wardCounts.values()].reduce((sum, counts) => sum + counts.homes, 0);
        const totalSchools = [...wardCounts.values()].reduce((sum, counts) => sum + counts.schools, 0);
        const totalHealth = [...wardCounts.values()].reduce((sum, counts) => sum + counts.health, 0);
        const totalFacilities = totalSchools + totalHealth;
        mapStatus.textContent = `${wards.length} wards · ${totalHomes} mapped homes · ${totalFacilities} facilities${updatedNote}`;

        selectWard(selectedWardName);
    };

    const mapDataUrl = wardMaps[0].dataset.mapDataUrl;

    if (mapDataUrl) {
        fetch(mapDataUrl, { headers: { Accept: 'application/json' } })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('The ward map data could not be loaded.');
                }

                return response.json();
            })
            .then((data) => {
                wardMaps.forEach((wardMap) => renderWardMap(wardMap, data));
            })
            .catch(() => {
                wardMaps.forEach((wardMap) => {
                    const mapStatus = wardMap.querySelector('[data-map-status]');
                    mapStatus.textContent = 'Ward map unavailable. Please try again later.';
                });
            });
    }
}
