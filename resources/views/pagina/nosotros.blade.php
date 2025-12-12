<?= view('templates/navbar', ['title' => 'Nosotros - Nelva Bienes Raíces']) ?>
    <style>
        /* Estilos para el contenido de la página Nosotros */
        
        * {
            font-family: "Roboto", sans-serif;
        }
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../images/Nosotros/portada.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 0px;
            position: relative;
            z-index: 1;
        }

        /* Mejora la responsividad del parallax */
        @media (max-width: 768px) {
            .hero {
                background-attachment: scroll; 
            }
        }
        
        .hero-content h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .hero-content p {
            font-size: 20px;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Sección Nosotros */
        .about-section {
            padding: 0px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .section-title p {
            color: #7f8c8d;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .about-content {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 40px;
            margin-bottom: 60px;
        }
        
        .about-text {
            flex: 1;
            min-width: 300px;
        }
        
        .about-text h3 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .about-text p {
            margin-bottom: 15px;
            color: #555;
        }
        
        .about-image {
            flex: 1;
            min-width: 300px;
        }
        
        .about-image img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .about-image img:hover {
            transform: scale(1.03);
        }
        
        /* Equipo */
        .team-section {
            background-color: #f9f9f9;
            padding: 0px 5%;
        }
        
        .team-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .team-members {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }
        
        .team-member {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            width: 340px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            opacity: 0;
            transform: translateY(30px);
        }

        .team-member.animated {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .team-member:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        /* Imágenes del equipo más pequeñas y mejoradas */
        .member-image {
            height: 280px; /* Reducida de 390px */
            overflow: hidden;
            position: relative;
            padding: 25px 25px 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 8px;
            transition: transform 0.5s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .team-member:hover .member-image img {
            transform: scale(1.05);
        }
        
        .member-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .member-info h4 {
            font-size: 20px;
            color: #1e3042;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .member-info .position {
            color: #0c1149;
            font-weight: 500;
            margin-bottom: 15px;
            font-size: 16px;
        }

        /* Agregar esta regla específica para el párrafo de descripción */
        .member-summary {
            text-align: left;
            color: #555; 
            font-weight: normal; 
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .value-card {
            text-align: center;
            padding: 30px;
            border-radius: 8px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            opacity: 0;
            transform: translateY(30px);
        }

        .value-card.animated {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .value-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }
        
        .value-icon {
            font-size: 40px;
            color: #FEB818;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .value-card:hover .value-icon {
            transform: scale(1.2);
        }
        
        .value-card h4 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .value-card p {
            color: #7f8c8d;
        }
        
        /* Animaciones base */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Elementos con animación inicial */
        

        .hero-content h1 {
            animation: slideInDown 1s ease-out 0.5s both;
        }
        
        .hero-content p {
            animation: slideInUp 1s ease-out 0.7s both;
        }

        .section-title {
            opacity: 0;
            animation: fadeIn 1s ease-out 0.3s forwards;
        }

        .about-content {
            opacity: 0;
            animation: fadeInUp 1s ease-out 0.5s forwards;
        }
        
        /* Responsive - CARDS MÁS COMPACTOS */
        @media (max-width: 1200px) {
            .team-member {
                width: 320px;
            }
        }

        @media (max-width: 992px) {
            .team-member {
                width: 300px;
            }
            
            .member-image {
                height: 260px;
                padding: 20px 20px 10px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                margin-top: 0px;
                height: 50vh;
            }
            
            .hero-content h1 {
                font-size: 36px;
            }
            
            .hero-content p {
                font-size: 18px;
            }
            
            .about-content {
                flex-direction: column;
            }
            
            .section-title h2 {
                font-size: 30px;
            }

            .team-member {
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }

            .member-image {
                height: 240px;
                padding: 20px 20px 10px;
            }

            .member-info {
                padding: 15px;
            }

            .member-info h4 {
                font-size: 18px;
            }

            .member-info .position {
                font-size: 15px;
            }

            .member-summary {
                font-size: 14px;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 576px) {
            .team-member {
                width: 100%;
                max-width: 350px;
            }

            .member-image {
                height: 220px;
                padding: 15px 15px 8px;
            }

            .member-info {
                padding: 12px;
            }

            .member-info h4 {
                font-size: 17px;
            }

            .member-summary {
                font-size: 13px;
                line-height: 1.5;
            }
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
            padding: 0 20px 20px;
        }

        .social-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .social-button i {
            margin-right: 8px;
        }

        .facebook-btn {
            background-color: #1877f2;
        }

        .whatsapp-btn {
            background-color: #25d366;
        }

        .social-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Estilos para el botón de ver perfil */
        .view-profile-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #0850a8 0%, #0a63ce 100%);
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(8, 80, 168, 0.3);
        }

        .view-profile-btn:hover {
            background: linear-gradient(135deg, #0a63ce 0%, #0c75e8 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(8, 80, 168, 0.4);
        }

        /* ANIMACIONES MEJORADAS PARA EL MODAL - CORREGIDAS */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0);
            transition: background-color 0.4s ease;
        }

        .modal.show {
            display: block;
            background-color: rgba(0,0,0,0.7);
        }

        .modal.hiding {
            background-color: rgba(0,0,0,0);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 2% auto;
            padding: 0;
            border-radius: 16px;
            width: 80%;
            max-width: 900px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
            transform: scale(0.7) translateY(-50px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.4);
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .modal.show .modal-content {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .modal.hiding .modal-content {
            transform: scale(0.7) translateY(50px);
            opacity: 0;
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 25px;
            color: #666;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            backdrop-filter: blur(5px);
        }

        .close-modal:hover {
            color: #333;
            background: rgba(255,255,255,1);
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .modal-body {
            padding: 40px;
            overflow-y: auto;
            flex: 1;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease 0.2s;
        }

        .modal.show .modal-body {
            opacity: 1;
            transform: translateY(0);
        }

        .modal.hiding .modal-body {
            opacity: 0;
            transform: translateY(-20px);
            transition-delay: 0s;
        }

        /* Estilos para el contenido del modal - ALINEACIÓN IZQUIERDA EN MÓVIL */
        .modal-profile {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .modal-profile-image {
            flex: 0 0 220px;
            text-align: center;
            opacity: 0;
            transform: translateX(-30px);
            transition: all 0.5s ease 0.3s;
        }

        .modal.show .modal-profile-image {
            opacity: 1;
            transform: translateX(0);
        }

        .modal.hiding .modal-profile-image {
            opacity: 0;
            transform: translateX(-30px);
            transition-delay: 0s;
        }

        .modal-profile-image img {
            width: 100%;
            max-width: 220px;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
            transition: all 0.4s ease;
            filter: grayscale(0.1);
        }

        .modal-profile-image img:hover {
            transform: scale(1.03) rotate(1deg);
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            filter: grayscale(0);
        }

        .modal-profile-info {
            flex: 1;
            min-width: 300px;
        }

        .modal-profile-info h2 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 32px;
            font-weight: 700;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease 0.4s;
        }

        .modal.show .modal-profile-info h2 {
            opacity: 1;
            transform: translateY(0);
        }

        .modal.hiding .modal-profile-info h2 {
            opacity: 0;
            transform: translateY(-20px);
            transition-delay: 0s;
        }

        .modal-profile-info .position {
            color: #FEB818;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.4s ease 0.45s;
        }

        .modal.show .modal-profile-info .position {
            opacity: 1;
            transform: translateY(0);
        }

        .modal.hiding .modal-profile-info .position {
            opacity: 0;
            transform: translateY(-15px);
            transition-delay: 0s;
        }

        .modal-section {
            margin-bottom: 30px;
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.5s ease;
        }

        .modal.show .modal-section {
            opacity: 1;
            transform: translateX(0);
        }

        .modal.hiding .modal-section {
            opacity: 0;
            transform: translateX(20px);
        }

        .modal.show .modal-section:nth-child(1) { transition-delay: 0.5s; }
        .modal.show .modal-section:nth-child(2) { transition-delay: 0.6s; }
        .modal.show .modal-section:nth-child(3) { transition-delay: 0.7s; }
        .modal.show .modal-section:nth-child(4) { transition-delay: 0.8s; }
        .modal.show .modal-section:nth-child(5) { transition-delay: 0.9s; }

        .modal.hiding .modal-section:nth-child(1) { transition-delay: 0s; }
        .modal.hiding .modal-section:nth-child(2) { transition-delay: 0s; }
        .modal.hiding .modal-section:nth-child(3) { transition-delay: 0s; }
        .modal.hiding .modal-section:nth-child(4) { transition-delay: 0s; }
        .modal.hiding .modal-section:nth-child(5) { transition-delay: 0s; }

        .modal-section h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 22px;
            border-bottom: 3px solid #FEB818;
            padding-bottom: 10px;
            font-weight: 600;
            position: relative;
        }

        .modal-section h3::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 3px;
            background: #f89908;
            transition: width 0.6s ease;
        }

        .modal-section:hover h3::after {
            width: 100%;
        }

        .modal-section p, .modal-section ul {
            color: #555;
            line-height: 1.7;
            text-align: left; /* Aseguramos alineación izquierda */
        }

        .modal-section ul {
            padding-left: 20px;
        }

        .modal-section li {
            margin-bottom: 10px;
            position: relative;
            padding-left: 15px;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.4s ease;
            text-align: left; /* Aseguramos alineación izquierda */
        }

        .modal.show .modal-section li {
            opacity: 1;
            transform: translateX(0);
        }

        .modal.hiding .modal-section li {
            opacity: 0;
            transform: translateX(10px);
        }

        .modal.show .modal-section li:nth-child(1) { transition-delay: 0.6s; }
        .modal.show .modal-section li:nth-child(2) { transition-delay: 0.65s; }
        .modal.show .modal-section li:nth-child(3) { transition-delay: 0.7s; }
        .modal.show .modal-section li:nth-child(4) { transition-delay: 0.75s; }
        .modal.show .modal-section li:nth-child(5) { transition-delay: 0.8s; }
        .modal.show .modal-section li:nth-child(6) { transition-delay: 0.85s; }

        .modal-section li:before {
            content: "▸";
            color: #FEB818;
            font-weight: bold;
            position: absolute;
            left: 0;
            transition: transform 0.3s ease;
        }

        .modal-section li:hover:before {
            transform: translateX(3px);
        }

        .modal-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .skill-tag {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #2c3e50;
            padding: 8px 18px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            opacity: 0;
            transform: scale(0.8);
        }

        .modal.show .skill-tag {
            opacity: 1;
            transform: scale(1);
        }

        .modal.hiding .skill-tag {
            opacity: 0;
            transform: scale(0.8);
        }

        .modal.show .skill-tag:nth-child(1) { transition-delay: 0.7s; }
        .modal.show .skill-tag:nth-child(2) { transition-delay: 0.75s; }
        .modal.show .skill-tag:nth-child(3) { transition-delay: 0.8s; }
        .modal.show .skill-tag:nth-child(4) { transition-delay: 0.85s; }
        .modal.show .skill-tag:nth-child(5) { transition-delay: 0.9s; }
        .modal.show .skill-tag:nth-child(6) { transition-delay: 0.95s; }

        
        .modal-social-buttons {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease 1s;
        }

        .modal.show .modal-social-buttons {
            opacity: 1;
            transform: translateY(0);
        }

        .modal.hiding .modal-social-buttons {
            opacity: 0;
            transform: translateY(-20px);
            transition-delay: 0s;
        }

        /* Responsive para el modal - MEJORADO */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 5% auto;
                border-radius: 12px;
                max-height: 85vh;
            }
            
            .modal-body {
                padding: 25px;
            }
            
            .modal-profile {
                flex-direction: column;
                gap: 20px;
                text-align: left; /* Alineación izquierda en móvil */
            }
            
            .modal-profile-image {
                flex: 1;
                text-align: center; /* Solo la imagen centrada */
            }
            
            .modal-profile-image img {
                max-width: 180px;
            }

            .modal-profile-info {
                text-align: left; /* Todo el contenido de info alineado a la izquierda */
            }

            .modal-profile-info h2 {
                font-size: 26px;
                text-align: left;
            }

            .modal-profile-info .position {
                font-size: 18px;
                text-align: left;
            }

            .close-modal {
                top: 15px;
                right: 15px;
                width: 40px;
                height: 40px;
                font-size: 28px;
            }

            .modal-section h3 {
                text-align: left;
            }

            .modal-section p {
                text-align: left;
            }

            .modal-section ul {
                text-align: left;
            }

            .modal-social-buttons {
                justify-content: flex-start; /* Botones sociales alineados a la izquierda */
            }
        }

        @media (max-width: 576px) {
            .modal-content {
                width: 98%;
                margin: 2% auto;
            }
            
            .modal-body {
                padding: 20px;
            }
            
            .modal-profile-image img {
                max-width: 150px;
            }

            .modal-profile-info h2 {
                font-size: 24px;
            }

            .modal-profile-info .position {
                font-size: 16px;
            }

            .modal-section h3 {
                font-size: 20px;
            }
        }

        /* Animaciones para elementos de la sección filosofía */
        .philosophy-section {
            background-color: #f8f9fa;
            padding: 80px 5%;
        }

        .philosophy-section > div {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .philosophy-section h2 {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 20px;
            opacity: 0;
            transform: translateY(30px);
        }

        .philosophy-section h2.animated {
            animation: fadeInUp 1s ease-out forwards;
        }

        .philosophy-section > div > p {
            font-size: 18px;
            color: #555;
            max-width: 800px;
            margin: 0 auto 40px;
            opacity: 0;
            transform: translateY(30px);
        }

        .philosophy-section > div > p.animated {
            animation: fadeInUp 1s ease-out 0.2s forwards;
        }

        .philosophy-features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
        }

        .philosophy-feature {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            width: 300px;
            text-align: left;
            opacity: 0;
            transform: translateY(30px);
        }

        .philosophy-feature.animated {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .philosophy-feature:nth-child(1).animated { animation-delay: 0.3s; }
        .philosophy-feature:nth-child(2).animated { animation-delay: 0.4s; }
        .philosophy-feature:nth-child(3).animated { animation-delay: 0.5s; }

        .philosophy-feature h3 {
            color: #022F4A;
            margin-bottom: 15px;
            font-size: 22px;
        }

        .philosophy-feature p {
            color: #555;
        }

        .download-btn {
            background: #FEB818;
            color: white;
            padding: 20px 40px;
            border-radius: 24px;
            display: inline-block;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(51, 62, 218, 0.2);
            opacity: 0;
            transform: translateY(30px);
        }

        .download-btn.animated {
            animation: fadeInUp 1s ease-out 0.6s forwards;
        }

        .download-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(51, 62, 218, 0.3);
        }


    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<!-- Contenido de la página Nosotros -->
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Sobre Nosotros</h1>
            <p>Conoce más sobre nuestra empresa y nuestro compromiso con encontrar el hogar perfecto para ti</p>
        </div>
    </section>

    <!-- Sección Nosotros -->
    <section class="about-section">
        <div class="section-title">
            <h2>Nuestra Historia</h2>
            <p>Más de 6 años ayudando a las personas a encontrar su hogar ideal</p>
        </div>
        
        <div class="about-content">
            <div class="about-text">
                <h3>Desarrolladora Nelva Bienes Raices S de R. L de C. V Desde 2019</h3>
                <p>Nelva Bienes Raíces es una empresa inmobiliaria 100% oaxaqueña con más de 5 años de experiencia, legalmente constituida cuya razón social es: Desarrolladora Nelva Bienes Raíces S. DE R.L DE C.V, la cual ofrece soluciones de alta calidad en la compra, venta y desarrollo de propiedades en el estado de oaxaca.</p>
            </div>
            <div class="about-image">
                <img src="images/Nosotros/rfc.webp" alt="Oficina de Bienes Raíces">
            </div>
        </div>

        <div class="about-content">
            <div class="about-text">
                <h3>Misión</h3>
                <p>Nos apasiona crear historias para que tú y tu familia cuenten con soluciones inmobiliarias efectivas, con garantía y alta plusvalía en las playas más importantes del estado de Oaxaca.</p>
            </div>

            <div class="about-text">
                <h3>Visión</h3>
                <p>Consolidarnos como una empresa líder e innovadora en el estado de Oaxaca, capaz de brindarte soluciones inmobiliarias integrales que generen bienestar económico, social y ambiental para ti y tu familia.</p>
            </div>

        </div>

        </div>

            <div class="about-content">
            <div class="about-image">
                <img src="images/Nosotros/propuesta1.JPG" style="width: 350px;" alt="Oficina de Bienes Raíces">
            </div>
            <div class="about-image">
                <img src="images/Nosotros/propuesta2.JPG" style="width: 350px;" alt="Oficina de Bienes Raíces">
            </div>
        </div>
        
    </section>

    <!-- Sección Equipo -->
    <section class="team-section">
        <div class="team-container">
            <div class="section-title">
                <h2>Nuestro Equipo</h2>
                <p>Profesionales dedicados a brindarte el mejor servicio</p>
            </div>

            <div class="team-members">
                <!-- Nelson Valencia Juarez -->
                <div class="team-member" data-member="nelson">
                    <div class="member-image">
                        <img src="images/Nosotros/nelson.png" alt="Nelson Valencia Juarez">
                    </div>
                    <div class="member-info">
                        <h4>Nelson Valencia Juárez</h4>
                        <p class="position">Director General</p>
                        <p class="member-summary">Líder visionario y fundador de NELVA Bienes Raíces, ha impulsado el crecimiento y posicionamiento de la empresa...</p>
                        <a href="#" class="view-profile-btn">Ver perfil completo</a>
                    </div>
                    <div class="social-buttons">
                        <a href="https://www.facebook.com/profile.php?id=61565124028229" target="_blank" class="social-button facebook-btn">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>
                        <a href="https://wa.me/529581130282?text=Hola%2C%20me%20interesa%20conocer%20m%C3%A1s%20sobre%20los%20terrenos%20disponibles." target="_blank" class="social-button whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
                
                <!-- Victoria López García -->
                <div class="team-member" data-member="victoria">
                    <div class="member-image">
                        <img src="images/Nosotros/vicky.png" alt="Victoria López García">
                    </div>
                    <div class="member-info">
                        <h4>Victoria López García</h4>
                        <p class="position">Administración y Finanzas</p>
                        <p class="member-summary">Egresado en Gestión Empresarial, con sólida formación en la planificación, organización y dirección de empresas...</p>
                        <a href="#" class="view-profile-btn">Ver perfil completo</a>
                    </div>
                    <div class="social-buttons">
                        <a href="https://www.facebook.com/profile.php?id=61563572601730" target="_blank" class="social-button facebook-btn">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>
                        <a href="https://wa.me/529581071940?text=Hola%2C%20me%20interesa%20conocer%20m%C3%A1s%20sobre%20los%20terrenos%20disponibles." target="_blank" class="social-button whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
                
                <!-- Hermilo Valencia Santiago -->
                <div class="team-member" data-member="hermilo">
                    <div class="member-image">
                        <img src="images/Nosotros/hermilo.png" alt="Hermilo Valencia Santiago">
                    </div>
                    <div class="member-info">
                        <h4>Hermilo Valencia Santiago</h4>
                        <p class="position">Gestor de Propiedades</p>
                        <p class="member-summary">Profesional especializado en la administración, operación y mantenimiento de propiedades inmobiliarias, con experiencia en la atención a propietarios...</p>
                        <a href="#" class="view-profile-btn">Ver perfil completo</a>
                    </div>
                    <div class="social-buttons">
                        <a href="https://www.facebook.com/profile.php?id=61558112995090" target="_blank" class="social-button facebook-btn">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>
                        <a href="https://wa.me/529581162213?text=Hola%2C%20me%20interesa%20conocer%20m%C3%A1s%20sobre%20los%20terrenos%20disponibles." target="_blank" class="social-button whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Williams Hernández Gómez -->
                <div class="team-member" data-member="williams">
                    <div class="member-image">
                        <img src="images/Nosotros/william.png" alt="Williams Hernández Gómez">
                    </div>
                    <div class="member-info">
                        <h4>Williams Hernández Gómez</h4>
                        <p class="position">Coordinador de Sucursales</p>
                        <p class="member-summary">Profesional en Administración con formación de Maestría en Administración y experiencia en la coordinación, gestión y optimización de operaciones...</p>
                        <a href="#" class="view-profile-btn">Ver perfil completo</a>
                    </div>
                    <div class="social-buttons">
                        <a href="https://www.facebook.com/profile.php?id=61560542818881" target="_blank" class="social-button facebook-btn">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>
                        <a href="https://wa.me/529582721463?text=Hola%2C%20me%20interesa%20conocer%20m%C3%A1s%20sobre%20los%20terrenos%20disponibles." target="_blank" class="social-button whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
                
                <!-- Virginia Massiel Aviles Castillo -->
                <div class="team-member" data-member="virginia">
                    <div class="member-image">
                        <img src="images/Nosotros/massiel.png" alt="Virginia Massiel Aviles Castillo">
                    </div>
                    <div class="member-info">
                        <h4>Virginia Massiel Aviles Castillo</h4>
                        <p class="position">Gestión Comercial y Sistemas</p>
                        <p class="member-summary">Especializada en la implementación de estrategias de marketing digital y tradicional, generación de prospectos, seguimiento comercial...</p>
                        <a href="#" class="view-profile-btn">Ver perfil completo</a>
                    </div>
                    <div class="social-buttons">
                        <a href="https://www.facebook.com/profile.php?id=61583951460071" target="_blank" class="social-button facebook-btn">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>

                        <a href="https://wa.me/5219581199171?text=Hola%2C%20me%20interesa%20conocer%20m%C3%A1s%20sobre%20los%20terrenos%20disponibles." target="_blank" class="social-button whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
                
                <!-- Aranza Yunuen Mecinas Guevara -->
                <div class="team-member" data-member="aranza">
                    <div class="member-image">
                        <img src="images/Nosotros/yunuen.png" alt="Aranza Yunuen Mecinas Guevara">
                    </div>
                    <div class="member-info">
                        <h4>Aranza Yunuen Mecinas Guevara</h4>
                        <p class="position">Mentora comercial y de liderazgo </p>
                        <p class="member-summary">Profesional con amplia trayectoria en ventas regionales, coordinación inmobiliaria y desarrollo de negocios...</p>
                        <a href="#" class="view-profile-btn">Ver perfil completo</a>
                    </div>
                    <div class="social-buttons">
                        <a href="https://wa.me/5214778478320?text=Hola%2C%20me%20interesa%20conocer%20m%C3%A1s%20sobre%20los%20terrenos%20disponibles." target="_blank" class="social-button whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>


                <div class="team-member" data-member="zaira">
                    <div class="member-image">
                        <img src="images/Nosotros/zaira.png" alt="Aranza Yunuen Mecinas Guevara">
                    </div>
                    <div class="member-info">
                        <h4>Zaira Ramírez Olivera</h4>
                        <p class="position">Ventas</p>
                        <p class="member-summary">Profesional con experiencia en ventas, administración operativa y atención al cliente, desarrollada en empresas...</p>
                        <a href="#" class="view-profile-btn">Ver perfil completo</a>
                    </div>
                    <div class="social-buttons">
                        <a href="https://www.facebook.com/profile.php?id=61579643023779" target="_blank" class="social-button facebook-btn">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>
                        <a href="https://wa.me/5219581362522?text=Hola%2C%20me%20interesa%20conocer%20m%C3%A1s%20sobre%20los%20terrenos%20disponibles." target="_blank" class="social-button whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal para perfiles completos -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-body">
                <!-- El contenido se cargará dinámicamente aquí -->
            </div>
        </div>
    </div>
    
    <!-- Sección Valores -->
    <section class="values-section">
        <div class="section-title">
            <h2>Nuestros Valores</h2>
            <p>Principios que guían nuestro trabajo diario</p>
        </div>
        
        <div class="values-grid">
            <div class="value-card" data-delay="0">
                <div class="value-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h4>Confianza</h4>
                <p>Para nuestro equipo de Talento Humano es indispensable actuar de manera ética, con calidad y absoluta transparencia.</p>
            </div>
            
            <div class="value-card" data-delay="100">
                <div class="value-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h4>Honestidad</h4>
                <p>En nuestra organización nos comprometemos a tener conductas adecuadas y sinceras con nuestros inversionistas.</p>
            </div>
            
            <div class="value-card" data-delay="200">
                <div class="value-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h4>La Familia</h4>
                <p>Nuestro éxito se debe al cuidado que le demos a tus inversiones con nuestras acciones procuramos el bienestar de los tuyos.</p>
            </div>
            
            <div class="value-card" data-delay="300">
                <div class="value-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h4>Tranquilidad</h4>
                <p>Entraras en tranquilidad al saber que NELVA tiene unas finanzas sanas, lo que garantiza que tus propiedades tengan una alta plusvalía.</p>
            </div>

            <div class="value-card" data-delay="400">
                <div class="value-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4>Responsabilidad</h4>
                <p>En NELVA estamos comprometidos con la satisfacción de nuestros inversionistas, con la sociedad y el medio ambiente.</p>
            </div>
        </div>
    </section>

<!-- Nueva sección Filosofía -->
<section class="philosophy-section">
    <div>
        <h2>La mejor agencia inmobiliaria que puedes encontrar</h2>
        <p>Somos líderes en ofrecer soluciones inmobiliarias efectivas y con alta plusvalía en las mejores zonas de Oaxaca.</p>
        
        <div class="philosophy-features">
            <div class="philosophy-feature" data-delay="0">
                <h3>✓ Los Mejores Precios</h3>
                <p>Garantizamos las mejores opciones del mercado con excelente relación calidad-precio.</p>
            </div>
            
            <div class="philosophy-feature" data-delay="100">
                <h3>✓ Agencia Confiable</h3>
                <p>Más de 5 años de experiencia respaldan nuestra seriedad y profesionalismo.</p>
            </div>
            
            <div class="philosophy-feature" data-delay="200">
                <h3>✓ Precios Accesibles</h3>
                <p>Opciones para todos los presupuestos sin comprometer calidad o ubicación.</p>
            </div>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ asset('FILOSOFIA-NELVA-2024.pdf') }}" download class="download-btn">
                <div>
                    <h3 style="font-size: 24px; margin-bottom: 10px;">FILOSOFÍA NELVA</h3>
                    <p style="font-size: 18px; font-weight: bold; margin: 0;">6+ Años de experiencia</p>
                </div>
            </a>
        </div>
    </div>
</section>

<script>
// Datos de los miembros del equipo
const teamMembers = {
    nelson: {
        name: "Nelson Valencia Juárez",
        position: "Director General",
        image: "images/Nosotros/nelson.png",
        summary: "Líder visionario y fundador de NELVA Bienes Raíces, ha impulsado el crecimiento y posicionamiento de la empresa como una referencia en la costa oaxaqueña. Su enfoque estratégico y compromiso con el bienestar comunitario han permitido que numerosas familias, tanto locales como nacionales, encuentren un hogar en las hermosas playas de Oaxaca. Bajo su liderazgo, NELVA se ha distinguido por promover un desarrollo inmobiliario responsable, con bases en la confianza, la sustentabilidad y el acompañamiento profesional a cada cliente.",
        experience: [
            "Puesto: Director General",
            "Desarrolladora Nelva Bienes Raíces S de R.L de C.V. (2019-2026)",
            "Representante Municipal del Municipio de Santa María Tonameca, Oax.",
            "Premio Estatal INADEM 2016, sector avícola",
            "Lidera más de 20 proyectos inmobiliarios estratégicos en playas de Oaxaca.",
            "Desarrolla y ejecuta portafolios de inversión inmobiliaria con más de 1,000 inversionistas locales y nacionales.",
            "Gestiona relaciones con desarrolladores, inversionistas y autoridades.",
            "Promueve modelos de negocio rentables."
        ],
        skills: [
            "Dirección y gestión integral de desarrollos inmobiliarios",
            "Análisis de inversiones y estructuración de proyectos",
            "Evaluación de factibilidad financiera, comercial y urbana",
            "Conocimiento de normativas, uso de suelo y procesos de regularización",
            "Liderazgo ejecutivo orientado a resultados",
            "Toma de decisiones estratégicas en entornos dinámicos"
        ],
        education: [
            "Certificación ECO110 – Asesoría en Comercialización de Bienes Inmuebles",
            "Curso Finanzas para Desarrolladores Inmobiliarios",
            "Curso Finanzas Evaluación de Proyectos Inmobiliarios",
            "Curso Liderazgo Estratégico y Dirección de Equipos",
            "Curso Marketing Digital Inmobiliario",
            "Curso-Taller 'Ventas causa y efecto'",
            "Asistente The Real Estate Show / Expo Desarrollo Inmobiliario",
            "Asistente 4° Congreso Anual de Inversionistas Merida 2024",
            "Asistente II Simposium Inmobiliario AMPI",
            "Asistente 'El arte de Recaudar Capital' M2Capital",
            "Asistente LATAM Investment Workshop 2025"
        ],
        
    },
    hermilo: {
        name: "Hermilo Valencia Santiago",
        position: "Gestor de Propiedades",
        image: "images/Nosotros/hermilo.png",
        summary: "Profesional especializado en la administración, operación y mantenimiento de propiedades inmobiliarias, con experiencia en la atención a propietarios, arrendatarios e inversionistas. Destacado por la coordinación de servicios, supervisión de contratos, evaluación de proveedores, seguimiento puntual a pagos, cobranza y reportes financieros. Capacidad para resolver incidencias con rapidez, mantener relaciones sólidas con autoridades e inversionistas, y asegurar que cada propiedad cumpla estándares de seguridad, imagen y funcionalidad.",
        education: [
            "Licenciatura en derecho",
            "Licenciatura en pedagogía"
        ],
        experience: [
            "Puesto: Gestor de Propiedades",
            "17 años de experiencia en educación básica",
            "Representante sindical de la delegación d-1-280-costa",
            "Alcalde municipal de san francisco Cozoaltepec, Oax. 2018-2020",
            "Gestor inmobiliario",
            "Gestor de cobranza de banco azteca"
        ],
        skills: [
            "Detección de propiedades con alto potencial (crecimiento urbano, zonas turísticas, plusvalía emergente)",
            "Análisis de factibilidad para nuevos desarrollos y ampliaciones",
            "Administración integral de contratos y arrendamientos",
            "Gestión de información de mercado para proponer oportunidades de inversión",
            "Negociación efectiva y resolución de conflictos",
            "Liderazgo operativo y coordinación de equipos"
        ],
        educationAdditional: [
            "Asistente 4° Congreso Anual de Inversionistas Merida 2024",
            "Asistente II Simposium Inmobiliario AMPI",
            "Asistente 'El arte de Recaudar Capital' M2Capital"
        ],
        
    },
    williams: {
        name: "Williams Hernández Gómez",
        position: "Coordinador de Sucursales",
        image: "images/Nosotros/william.png",
        summary: "Profesional en Administración con formación de Maestría en Administración y experiencia en la coordinación, gestión y optimización de operaciones en diferentes sucursales. Especializado en supervisión de equipos, estandarización de procesos, seguimiento de indicadores y mejora continua para garantizar el cumplimiento de objetivos comerciales y administrativos.",
        education: [
            "Licenciatura en Administración de Empresas",
            "Maestría en Administración"
        ],
        experience: [
            "Puesto: Coordinador de Sucursales",
            "14 años de experiencia en educación superior",
            "Subdirector de Planeación y Vinculación y Académico del TecNM",
            "Emprendedor",
            "Asesor Inmobiliario Certificado"
        ],
        skills: [
            "Coordinación operativa de sucursales",
            "Gestión de proyectos académicos, administrativos y comerciales",
            "Evaluación y mejora continua de servicios",
            "Supervisión de personal y estructura organizacional",
            "Coordinación de áreas multidisciplinarias",
            "Gestión del cambio y adaptación organizacional"
        ],
        educationAdditional: [
            "Estandar de Competencia EC0110.02",
            "Miembro del Instituto Nacional de Administración Publica A.C",
            "Integrante de la Comisión del Módelo y Política de Financiamiento de la Educación Superior",
            "Auditor del Sistema de Gestión de Calidad (ISO 9001:2015)",
            "Diplomado de Educación Financiera (CONDUSEF)",
            "Asistente 4° Congreso Anual de Inversionistas Merida 2024",
            "Asistente II Simposium Inmobiliario AMPI",
            "Asistente 'El arte de Recaudar Capital' M2Capital",
            "Asistente LATAM Investment Workshop 2025"
        ],
        
    },

    victoria: {
        name: "Victoria López García",
        position: "Administración y Finanzas",
        image: "images/Nosotros/vicky.png",
        summary: "Egresado en Gestión Empresarial, con sólida formación en la planificación, organización y dirección de empresas, así como en la administración de recursos y la optimización de procesos. Posee conocimientos en finanzas, marketing, recursos humanos y operaciones, lo que le permite comprender integralmente el funcionamiento de una organización.",
        education: [
            "Ingeniera en Gestión Empresarial"
        ],
        experience: [
            "Puesto: Administración y Finanzas",
            "Responsable administrativo con la desarrolladora Nelva Bienes Raíces."
        ],
        skills: [
            "Diseño de estrategias, gestión de proyectos y coordinación de actividades internas.",
            "Evaluación de datos, resolución de problemas y elección de alternativas estratégicas.",
            "Manejo de conceptos clave en finanzas, marketing, operaciones y capital humano.",
            "Capacidad para guiar equipos, delegar tareas y fomentar un ambiente de trabajo productivo.",
            "Presentación de ideas, elaboración de informes y trabajo colaborativo."
        ],
        
    },

    virginia: {
        name: "Virginia Massiel Aviles Castillo",
        position: "Gestión Comercial y Sistemas",
        image: "images/Nosotros/massiel.png",
        summary: "Especializada en la implementación de estrategias de marketing digital y tradicional, generación de prospectos, seguimiento comercial y posicionamiento de marca. Capaz de liderar equipos multidisciplinarios, mejorar indicadores de servicio, impulsar la rentabilidad y participar activamente en la expansión de nuevas líneas de negocio e iniciativas inmobiliarias.",
        education: [
            "Licenciatura en Innovación de Negocios y Mercadotecnia"
        ],
        experience: [
            "Puesto: Gestión Comercial y Sistemas",
            "Responsable administrativo en Roga Materiales",
            "Control de norma en Roga Materiales"
        ],
        skills: [
            "Implementación de estrategias de marketing digital y tradicional",
            "Posicionamiento de marca y gestión de campañas",
            "Análisis de mercado y detección de oportunidades comerciales",
            "Administración y control de sistemas internos de gestión",
            "Control de cumplimiento normativo y operativo"
        ],
        educationAdditional: [
            "Atención y trato al Cliente",
            "Concientización en seguridad de la información",
            "Manual de prevención de fraudes",
            "Prevención del lavado de dinero."
        ],
        
    },

    aranza: {
        name: "Aranza Yunuen Mecinas Guevara",
        position: "Mentora comercial y de liderazgo",
        image: "images/Nosotros/yunuen.png",
        summary: "Profesional con amplia trayectoria en ventas regionales, coordinación inmobiliaria y desarrollo de negocios, destacando por la capacidad de liderar equipos, generar alianzas estratégicas y potenciar el crecimiento comercial. Con experiencia en la gestión de ventas a nivel regional, supervisión de operaciones inmobiliarias y asesoría especializada en propiedades y proyectos en zonas costeras.",
        
        experience: [
            "Mentora comercial y de liderazgo",
            "Toastmasters Influencia Persuasiva",
            "Gerente de ventas regional de la empresa ecology",
            "Coordinadora regional de inmoplus bienes raíces",
            "Asesor inmobiliario con expertice en costas",
            "Networker estratégico",
            "Tallerista"
        ],
        skills: [
            "Dirección de equipos de ventas y motivación para el logro de metas regionales",
            "Creación de alianzas comerciales y fortalecimiento de relaciones profesionales",
            "Experiencia como tallerista en temas de ventas, atención al cliente y desarrollo profesional",
            "Orientación al servicio y acompañamiento personalizado en cada etapa del proceso",
            "Capacidad para comprender las necesidades de clientes y equipos, generando confianza y relaciones duraderas",
            "Habilidad para crear vínculos auténticos, establecer relaciones estratégicas y generar ambientes colaborativos."
        ],
        educationAdditional: [
            "Conferencia inspiracional",
            "Taller en Ventas tridimensionales",
            "Taller manejo de objeciones",
            "Curso Marketing Digital Inmobiliario",
            "Curso-Taller “Ventas causa y efecto”",
            "Asistente 4° Congreso Anual de Inversionistas Merida 2024"
        ],
    },

    zaira: {
        name: "Zaira Ramírez Olivera",
        position: "Ventas",
        image: "images/Nosotros/zaira.png",
        summary: "Profesional con experiencia en ventas, administración operativa y atención al cliente, desarrollada en empresas reconocidas del sector automotriz y de servicios. Destaca por su capacidad para gestionar procesos administrativos, coordinar operaciones y brindar una atención eficiente y orientada a resultados. Con trayectoria en áreas comerciales y operativas, aporta organización, responsabilidad y un enfoque sólido al cumplimiento de metas y la satisfacción del cliente.",
        education: [
            "Ingeniera en Mecánica Industrial "
        ],
        experience: [
            "Puesto: Ventas",
            "Responsable administrativo Empresa Italika",
            "Responsable Fast Line",
            "Personal operativo en Mercedes Benz"
        ],
        skills: [
            "Control de documentos, manejo de procesos internos, reportes y organización operativa",
            "Experiencia en coordinación de actividades, flujo de trabajo y soporte operativo",
            "Capacidad para responder con eficacia a situaciones operativas y administrativas",
            "Colaboración constante con áreas comerciales y administrativas para alcanzar objetivos comunes",
            "Manejo preciso de tareas, cumplimiento de procesos y enfoque en resultados"
        ],
        educationAdditional: [
            "Experiencia en procesos productivos, mejora continua y análisis técnico",
            "Concientización en seguridad de la información ",
            "Cursos: atención al cliente básico, normatividad y seguridad, Autocad, SolidWorks, CNC,seguridad industrial."
        ],
    }

};

// Función para abrir el modal con animaciones - CORREGIDA
function openProfileModal(memberId) {
    const member = teamMembers[memberId];
    if (!member) return;
    
    const modal = document.getElementById('profileModal');
    const modalBody = document.querySelector('.modal-body');
    
    // Construir el contenido del modal
    modalBody.innerHTML = `
        <div class="modal-profile">
            <div class="modal-profile-image">
                <img src="${member.image}" alt="${member.name}">
            </div>
            <div class="modal-profile-info">
                <h2>${member.name}</h2>
                <p class="position">${member.position}</p>
                
                <div class="modal-section">
                    <h3>Resumen Profesional</h3>
                    <p>${member.summary}</p>
                </div>
                
                ${member.education ? `
                <div class="modal-section">
                    <h3>Escolaridad</h3>
                    <ul>
                        ${member.education.map(item => `<li>${item}</li>`).join('')}
                    </ul>
                </div>
                ` : ''}
                
                <div class="modal-section">
                    <h3>Experiencia Destacada</h3>
                    <ul>
                        ${member.experience.map(item => `<li>${item}</li>`).join('')}
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Habilidades Principales</h3>
                    <div class="modal-skills">
                        ${member.skills.map(skill => `<span class="skill-tag">${skill}</span>`).join('')}
                    </div>
                </div>
                
                ${member.educationAdditional ? `
                <div class="modal-section">
                    <h3>Cursos y Certificaciones</h3>
                    <ul>
                        ${member.educationAdditional.map(item => `<li>${item}</li>`).join('')}
                    </ul>
                </div>
                ` : ''}
                
                
            </div>
        </div>
    `;
    
    // Mostrar el modal con animación
    modal.style.display = 'block';
    
    // Forzar reflow para que la animación funcione
    void modal.offsetWidth;
    
    // Resetear el scroll del modal-body al inicio
    modalBody.scrollTop = 0;
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Prevenir scroll del body
}

// Cerrar el modal con animación
function closeProfileModal() {
    const modal = document.getElementById('profileModal');
    const modalBody = document.querySelector('.modal-body');
    
    // Resetear el scroll para la próxima apertura
    modalBody.scrollTop = 0;
    
    // Iniciar animación de salida
    modal.classList.remove('show');
    modal.classList.add('hiding');
    
    // Esperar a que termine la animación antes de ocultar completamente
    setTimeout(() => {
        modal.classList.remove('hiding');
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Restaurar scroll del body
    }, 500); // Tiempo igual a la duración de la animación
}

// Animaciones al hacer scroll - SIN EFECTO DE PROGRESO
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.team-member, .value-card, .philosophy-feature, .philosophy-section h2, .philosophy-section > div > p, .download-btn');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                element.classList.add('animated');
                // Dejar de observar el elemento una vez que se ha animado
                observer.unobserve(element);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observar todos los elementos que necesitan animación
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

// Event listeners cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar animaciones al scroll
    initScrollAnimations();
    
    // Agregar evento a los botones de ver perfil
    document.querySelectorAll('.view-profile-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const memberCard = this.closest('.team-member');
            const memberId = memberCard.getAttribute('data-member');
            openProfileModal(memberId);
        });
    });
    
    // Agregar evento al botón de cerrar modal
    document.querySelector('.close-modal').addEventListener('click', closeProfileModal);
    
    // Cerrar modal al hacer clic fuera del contenido
    document.getElementById('profileModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProfileModal();
        }
    });

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProfileModal();
        }
    });
});

 
</script>

<?= view('templates/footer') ?>

</body>
</html>