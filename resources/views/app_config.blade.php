<script>
    window.AppConfig = {
        fraccionamientoId: @json(isset($datosFraccionamiento['id']) ? $datosFraccionamiento['id'] : null),
        fraccionamientoNombre: @json(isset($datosFraccionamiento['nombre']) ? $datosFraccionamiento['nombre'] : '')
    };

    // Validar que las variables estén definidas
    if (window.AppConfig.fraccionamientoId === null || window.AppConfig.fraccionamientoNombre === '') {
        console.error('❌ Las variables de fraccionamiento no están definidas o son inválidas. Usando valores por defecto.');
        window.AppConfig.fraccionamientoId = window.AppConfig.fraccionamientoId ?? 0;
        window.AppConfig.fraccionamientoNombre = window.AppConfig.fraccionamientoNombre || 'default';
    } else {
        console.log('✅ Configuración de fraccionamiento cargada:', window.AppConfig);
    }
</script>