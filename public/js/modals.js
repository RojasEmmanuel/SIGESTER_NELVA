document.addEventListener('DOMContentLoaded', function () {
    /* ===========================
       VALIDAR AppConfig
       =========================== */
    if (!window.AppConfig || window.AppConfig.fraccionamientoId === null || !window.AppConfig.fraccionamientoNombre) {
        console.error('❌ Configuración de fraccionamiento no disponible en modals.js');
        window.AppConfig = window.AppConfig || {};
        window.AppConfig.fraccionamientoId = window.AppConfig.fraccionamientoId ?? 0;
        window.AppConfig.fraccionamientoNombre = window.AppConfig.fraccionamientoNombre || 'default';
    } else {
        console.log('✅ Configuración de fraccionamiento cargada en modals.js:', window.AppConfig);
    }

    /* ===========================
       ELEMENTOS DOM
       =========================== */
    const reservationModal = document.getElementById('reservationModal');
    const closeModal = document.getElementById('closeModal');
    const openReservationModal = document.getElementById('openReservationModal');
    const reservationForm = document.getElementById('reservationForm');
    const depositFields = document.getElementById('depositFields');
    const verbalReceipt = document.getElementById('verbalReceipt');
    const depositReceipt = document.getElementById('depositReceipt');
    const verbalName = document.getElementById('verbalName');
    const depositName = document.getElementById('depositName');
    const verbalLots = document.getElementById('verbalLots');
    const depositLots = document.getElementById('depositLots');
    const depositAmount = document.getElementById('depositAmount');
    const deadlineDate = document.getElementById('deadlineDate');
    const referenceNumber = document.getElementById('referenceNumber');
    const closeAfterVerbal = document.getElementById('closeAfterVerbal');
    const whatsappShare = document.getElementById('whatsappShare');
    const verbalWhatsappShare = document.getElementById('verbalWhatsappShare');
    const lotList = document.getElementById('lotList');
    const addLotBtn = document.getElementById('addLotBtn');

    const calculationModal = document.getElementById('calculationModal');
    const closeCalculationModal = document.getElementById('closeCalculationModal');
    const openCalculationModal = document.getElementById('openCalculationModal');
    const calculateBtn = document.getElementById('calculateBtn');
    const lotDetails = document.getElementById('lotDetails');

    /* ===========================
       UTIL / MAPA - STATUS MAPS
       =========================== */
    const STATUS_CLASS_MAP = {
        'disponible': 'status-disponible',
        'apartadoPalabra': 'status-apartado',
        'apartadoVendido': 'status-apartado',
        'vendido': 'status-vendido',
        'no disponible': 'status-no-disponible'
    };

    const STATUS_LABEL_MAP = {
        'disponible': 'Disponible',
        'apartadoPalabra': 'Apartado (Palabra)',
        'apartadoVendido': 'Apartado (Vendido)',
        'vendido': 'Vendido',
        'no disponible': 'No Disponible'
    };

    function getStatusClass(status) {
        return STATUS_CLASS_MAP[status] || 'status-no-disponible';
    }

    function formatStatus(status) {
        return STATUS_LABEL_MAP[status] || status || 'No Disponible';
    }

    /* ===========================
       CAMPOS DINÁMICOS LOTES
       =========================== */
    function addLotField() {
        if (!lotList) {
            console.error('❌ Elemento lotList no encontrado');
            return;
        }
        const newLotItem = document.createElement('div');
        newLotItem.className = 'lot-item';
        newLotItem.innerHTML = `
            <input type="text" class="form-control lot-number" required placeholder="Ej. 12, 5, etc.">
            <button type="button" class="remove-lot">
                <i class="fas fa-times"></i>
            </button>
        `;
        lotList.appendChild(newLotItem);

        const removeBtn = newLotItem.querySelector('.remove-lot');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                removeLotField(newLotItem);
            });
        }
    }

    function removeLotField(lotItem) {
        if (!lotList || !lotItem) return;
        if (lotList.children.length > 1) {
            lotList.removeChild(lotItem);
        } else {
            alert('Debe especificar al menos un lote');
        }
    }

    if (addLotBtn) {
        addLotBtn.addEventListener('click', addLotField);
    } else {
        console.warn('⚠️ Botón addLotBtn no encontrado');
    }

    document.querySelectorAll('.remove-lot').forEach(btn => {
        btn.addEventListener('click', function () {
            const lotItem = this.closest('.lot-item');
            removeLotField(lotItem);
        });
    });

    /* ===========================
       MODALES: abrir/cerrar y reset
       =========================== */
    if (openReservationModal) {
        openReservationModal.addEventListener('click', function () {
            if (!reservationModal) {
                console.error('❌ Modal de reservación no encontrado');
                return;
            }
            reservationModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    } else {
        console.warn('⚠️ Botón openReservationModal no encontrado');
    }

    if (openCalculationModal) {
        openCalculationModal.addEventListener('click', function () {
            if (!calculationModal) {
                console.error('❌ Modal de cálculo no encontrado');
                return;
            }
            calculationModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    } else {
        console.warn('⚠️ Botón openCalculationModal no encontrado');
    }

    if (closeModal) {
        closeModal.addEventListener('click', function () {
            if (!reservationModal) return;
            reservationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetReservationForm();
        });
    } else {
        console.warn('⚠️ Botón closeModal no encontrado');
    }

    if (closeCalculationModal) {
        closeCalculationModal.addEventListener('click', function () {
            if (!calculationModal) return;
            calculationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetCalculationForm();
        });
    } else {
        console.warn('⚠️ Botón closeCalculationModal no encontrado');
    }

    if (reservationModal) {
        reservationModal.addEventListener('click', function (e) {
            if (e.target === reservationModal) {
                reservationModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                resetReservationForm();
            }
        });
    } else {
        console.error('❌ Modal de reservación no encontrado');
    }

    if (calculationModal) {
        calculationModal.addEventListener('click', function (e) {
            if (e.target === calculationModal) {
                calculationModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                resetCalculationForm();
            }
        });
    } else {
        console.error('❌ Modal de cálculo no encontrado');
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (reservationModal) reservationModal.style.display = 'none';
            if (calculationModal) calculationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetReservationForm();
            resetCalculationForm();
        }
    });

    document.querySelectorAll('input[name="reservationType"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (depositFields) {
                depositFields.style.display = this.value === 'deposit' ? 'block' : 'none';
            }
        });
    });

    function resetReservationForm() {
        if (!reservationForm) return;
        reservationForm.reset();
        reservationForm.style.display = 'block';
        if (verbalReceipt) verbalReceipt.style.display = 'none';
        if (depositReceipt) depositReceipt.style.display = 'none';
        if (depositFields) depositFields.style.display = 'none';

        if (lotList) {
            while (lotList.children.length > 1) {
                lotList.removeChild(lotList.lastChild);
            }
            const first = document.querySelector('.lot-number');
            if (first) first.value = '';
        }
    }

    function resetCalculationForm() {
        const calcForm = document.getElementById('calculationForm');
        if (calcForm) calcForm.reset();
        if (lotDetails) lotDetails.style.display = 'none';
    }

    /* ===========================
       CÁLCULO DE COSTO
       =========================== */
    if (calculateBtn) {
        calculateBtn.addEventListener('click', async function () {
            const lotNumberInput = document.getElementById('lotNumber');
            const lotError = document.getElementById('lotError');
            if (!lotNumberInput) {
                console.error('❌ Input lotNumber no encontrado');
                return;
            }
            const lotNumber = lotNumberInput.value.trim();

            if (!lotNumber) {
                if (lotError) {
                    lotError.textContent = 'Por favor ingrese un número de lote';
                    lotError.style.display = 'block';
                }
                return;
            }
            if (!/^\d+$/.test(lotNumber)) {
                if (lotError) {
                    lotError.textContent = 'Por favor ingrese un número de lote válido';
                    lotError.style.display = 'block';
                }
                return;
            }

            try {
                calculateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Calculando...';
                calculateBtn.disabled = true;
                if (lotError) lotError.style.display = 'none';

                const fraccionamientoId = window.AppConfig.fraccionamientoId;
                const url = `/asesor/fraccionamiento/${fraccionamientoId}/lote/${encodeURIComponent(lotNumber)}`;

                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 10000);

                const response = await fetch(url, {
                    signal: controller.signal,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                clearTimeout(timeoutId);

                const contentType = response.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    throw new Error('Respuesta del servidor no es JSON');
                }

                const data = await response.json();
                if (!response.ok) throw new Error(data.message || `Error ${response.status}`);

                if (data.success) {
                    const lote = data.lote || {};
                    const status = lote.estatus || 'no disponible';
                    const statusBadge = document.getElementById('statusBadge');
                    if (statusBadge) {
                        statusBadge.textContent = formatStatus(status);
                        statusBadge.className = 'status-badge ' + getStatusClass(status);
                    }

                    const el = id => document.getElementById(id);
                    if (el('lotId')) el('lotId').textContent = lote.id || 'N/A';
                    if (el('lotBlock')) el('lotBlock').textContent = lote.manzana || 'N/A';
                    if (el('lotArea')) el('lotArea').textContent = `${lote.area_total ? lote.area_total.toLocaleString('es-MX') : '0'} m²`;

                    if (lote.medidas) {
                        if (el('lotNorth')) el('lotNorth').textContent = `${lote.medidas.norte || '0'} m`;
                        if (el('lotSouth')) el('lotSouth').textContent = `${lote.medidas.sur || '0'} m`;
                        if (el('lotEast')) el('lotEast').textContent = `${lote.medidas.oriente || '0'} m`;
                        if (el('lotWest')) el('lotWest').textContent = `${lote.medidas.poniente || '0'} m`;
                    } else {
                        if (el('lotNorth')) el('lotNorth').textContent = 'No disponible';
                        if (el('lotSouth')) el('lotSouth').textContent = 'No disponible';
                        if (el('lotEast')) el('lotEast').textContent = 'No disponible';
                        if (el('lotWest')) el('lotWest').textContent = 'No disponible';
                    }

                    const totalCost = lote.costo_total || 0;
                    if (el('totalCost')) el('totalCost').textContent = `$${totalCost.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} MXN`;

                    if (lotDetails) lotDetails.style.display = 'block';
                } else {
                    throw new Error(data.message || 'Lote no encontrado');
                }
            } catch (error) {
                console.error('Error en cálculo:', error);
                if (lotError) {
                    if (error.name === 'AbortError') {
                        lotError.textContent = 'La solicitud tardó demasiado tiempo. Intente nuevamente.';
                    } else if (error.message.includes('JSON')) {
                        lotError.textContent = 'Error en la respuesta del servidor.';
                    } else {
                        lotError.textContent = error.message || 'Error al calcular el costo del lote.';
                    }
                    lotError.style.display = 'block';
                }
                if (lotDetails) lotDetails.style.display = 'none';
            } finally {
                calculateBtn.innerHTML = '<i class="fas fa-calculator"></i> Calcular';
                calculateBtn.disabled = false;
            }
        });
    } else {
        console.warn('⚠️ Botón calculateBtn no encontrado');
    }

    /* ===========================
       ENVÍO FORMULARIO APARTADO
       =========================== */
    if (reservationForm) {
        reservationForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const reservationType = document.querySelector('input[name="reservationType"]:checked')?.value || 'verbal';
            const firstName = (document.getElementById('firstName')?.value || '').trim();
            const lastName = (document.getElementById('lastName')?.value || '').trim();
            const lotNumbers = Array.from(document.querySelectorAll('.lot-number'))
                .map(input => input.value.trim())
                .filter(v => v !== '');

            if (!firstName || !lastName) {
                alert('Por favor complete todos los campos requeridos');
                return;
            }
            if (lotNumbers.length === 0) {
                alert('Por favor ingrese al menos un número de lote');
                return;
            }

            const randomRef = Math.floor(1000 + Math.random() * 9000);
            const fraccionamientoNombre = window.AppConfig.fraccionamientoNombre || 'default';

            if (reservationType === 'verbal') {
                if (verbalName) verbalName.textContent = `${firstName} ${lastName}`;
                if (verbalLots) verbalLots.textContent = lotNumbers.join(', ');
                const deadline = new Date();
                deadline.setDate(deadline.getDate() + 2);
                if (deadlineDate) deadlineDate.textContent = deadline.toLocaleString('es-MX', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
                });

                const verbalMessage = `Hola ${firstName}, tu apartado de palabra para el lote(s) ${lotNumbers.join(', ')} en ${fraccionamientoNombre} ha sido registrado. Tienes hasta el ${deadline.toLocaleDateString('es-MX')} para confirmar.`;
                if (verbalWhatsappShare) verbalWhatsappShare.href = `https://wa.me/?text=${encodeURIComponent(verbalMessage)}`;

                reservationForm.style.display = 'none';
                if (verbalReceipt) verbalReceipt.style.display = 'block';
            } else {
                const amount = document.getElementById('amount')?.value;
                if (!amount || amount < 1000) {
                    alert('Por favor ingrese un monto válido (mínimo $1,000 MXN)');
                    return;
                }
                if (depositName) depositName.textContent = `${firstName} ${lastName}`;
                if (depositLots) depositLots.textContent = lotNumbers.join(', ');
                if (depositAmount) depositAmount.textContent = parseFloat(amount).toLocaleString('es-MX', { minimumFractionDigits: 2 });

                if (referenceNumber) referenceNumber.textContent = randomRef;

                const depositMessage = `Hola ${firstName}, para apartar el lote(s) ${lotNumbers.join(', ')} en ${fraccionamientoNombre} realiza un depósito de $${amount} MXN a la cuenta BBVA. Referencia: ${fraccionamientoNombre.substring(0, 3)}-${randomRef}`;
                if (whatsappShare) whatsappShare.href = `https://wa.me/?text=${encodeURIComponent(depositMessage)}`;

                reservationForm.style.display = 'none';
                if (depositReceipt) depositReceipt.style.display = 'block';
            }
        });
    } else {
        console.warn('⚠️ Formulario reservationForm no encontrado');
    }

    if (closeAfterVerbal) {
        closeAfterVerbal.addEventListener('click', function () {
            if (reservationModal) reservationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetReservationForm();
        });
    } else {
        console.warn('⚠️ Botón closeAfterVerbal no encontrado');
    }

    /* ===========================
       FUNCIONES GLOBALES
       =========================== */
    window.openCalculationForLote = function (loteNumber) {
        const lotInput = document.getElementById('lotNumber');
        if (!lotInput) {
            console.error('❌ Input lotNumber no encontrado para cálculo');
            return;
        }
        lotInput.value = loteNumber;
        if (!calculationModal) {
            console.error('❌ Modal de cálculo no encontrado');
            return;
        }
        calculationModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            const btn = document.getElementById('calculateBtn');
            if (btn) btn.click();
            else console.error('❌ Botón calculateBtn no encontrado');
        }, 500);
    };

    window.openReservationForLote = function (loteNumber) {
        if (!lotList) {
            console.error('❌ Elemento lotList no encontrado');
            return;
        }
        while (lotList.children.length > 1) {
            lotList.removeChild(lotList.lastChild);
        }
        const first = document.querySelector('.lot-number');
        if (first) first.value = loteNumber;
        else console.error('❌ Input .lot-number no encontrado');

        if (!reservationModal) {
            console.error('❌ Modal de reservación no encontrado');
            return;
        }
        reservationModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    /* ===========================
       INICIALIZACIÓN FINAL
       =========================== */
    console.log('✅ Script de modales cargado correctamente');
});