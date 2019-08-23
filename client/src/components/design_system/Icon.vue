<template>
    <div class="icon-wrapper" @click="$emit('click', $event)" @keyup="$emit('keyup', $event)" @keydown="$emit('keydown', $event)">
        <svg v-if="name === 'qr-code'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M5.083 1H1v4.083h4.083V1zm0 9.917H1V15h4.083v-4.083zM10.917 1H15v4.083h-4.083V1zM15 6.25h-1.167v3.5h-2.916v1.167H15V6.25zm-4.083 5.833H15V15h-1.167v-1.75h-1.75V15h-1.166v-2.917zM9.75 1h-3.5v1.167h2.333v2.916H9.75V1zM8.583 6.25h3.5v2.333h-1.166V7.417H9.75V9.75H7.417v1.167H9.75V15H8.583v-2.917H6.25v-3.5h2.333V6.25zm-1.166 7H6.25V15h1.167v-1.75zM3.333 8.583h1.75V9.75h-1.75V8.583zm4.084-5.25H6.25V6.25H1v3.5h1.167V7.417h5.25V3.333z" />
        </svg>

        <svg v-if="name === 'undo-arrow'" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M2.502 12.333a6.996 6.996 0 0 1-1.405-3.168l1.973-.331a4.982 4.982 0 0 0 1.003 2.26l-1.571 1.239zM6.834 14.903a7.015 7.015 0 0 1-2.976-1.259l1.186-1.611c.624.459 1.358.77 2.122.898l-.332 1.972zM9.165 14.903l-.33-1.973a4.99 4.99 0 0 0 2.209-.964l1.219 1.586a6.997 6.997 0 0 1-3.098 1.351zM8 1c-1.873 0-3.65.759-4.948 2.052L.9.9.2 7.3l6.4-.7-2.135-2.135A5.023 5.023 0 0 1 8 3c2.757 0 5 2.243 5 5 0 1.06-.327 2.072-.947 2.928l1.621 1.173A6.96 6.96 0 0 0 15 8c0-3.86-3.141-7-7-7z"/>
        </svg>

        <svg v-if="name === 'pencil-edit'" viewBox="2 2 12 12" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M8.11 4.571l3.319 3.32-5.884 5.883a.685.685 0 0 1-.528.226H2.754C2.302 14 2 13.698 2 13.246v-2.263c0-.226.075-.377.226-.528L8.11 4.57zm5.653.04a.765.765 0 0 1 0 1.108l-1.425 1.424-3.48-3.481 1.423-1.425a.765.765 0 0 1 1.108 0l2.374 2.374z"/>
        </svg>

        <svg v-if="name === 'pencil-edit-inverted'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z"/>
        </svg>

        <svg v-if="name === 'delete-icon'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <g fill="#444450" fill-rule="nonzero">
                <path class="path" d="M2 6v8c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V6H2z"/>
                <path class="path" d="M12 3V1c0-.6-.4-1-1-1H5c-.6 0-1 .4-1 1v2H0v2h16V3h-4zm-2 0H6V2h4v1z"/>
            </g>
        </svg>

        <svg v-if="name === 'duplicate-icon'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <g fill-rule="nonzero">
                <path class="path"
                      d="M11 12H1c-.553 0-1-.447-1-1V1c0-.552.447-1 1-1h10c.553 0 1 .448 1 1v10c0 .553-.447 1-1 1z"/>
                <path class="path" d="M15 16H4v-2h10V4h2v11c0 .553-.447 1-1 1z"/>
            </g>
        </svg>

        <svg v-if="name === 'sort-arrow'" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 8 8">
            <path class="path" transform="translate(-4 -4)" d="M12 8.231l-.836-.787-2.567 2.419V4H7.403v5.863l-2.567-2.42L4 8.232 8 12z"/>
        </svg>

        <svg v-if="name === 'calendar'" viewBox="0 0 16 16"
             xmlns="http://www.w3.org/2000/svg">
            <path
                d="M15 2h-2V1c0-.6-.4-1-1-1h-1c-.6 0-1 .4-1 1v1H6V1c0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1v1H1c-.6 0-1 .4-1 1v12c0 .6.4 1 1 1h14c.6 0 1-.4 1-1V3c0-.6-.4-1-1-1zm-1 12H2V7h12v7z"
                fill-rule="nonzero"/>
        </svg>

        <!-- 'back': left angle icon -->
        <svg v-if="name === 'back'" viewBox="-3 0 14 17" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path class="path" d="M6.555 17L.23 9.157a1.058 1.058 0 0 1 0-1.314L6.555 0 8 1.316 2.204 8.5l5.795 7.183L6.555 17z"/>
        </svg>

        <!-- 'save': floppy icon  -->
        <svg v-if="name === 'save'" viewBox="0 0 12 14" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M10.238.262A.794.794 0 0 0 9.625 0H8.75v2.625c0 .525-.35.875-.875.875H3.5c-.525 0-.875-.35-.875-.875V0H.875C.35 0 0 .35 0 .875v12.25c0 .525.35.875.875.875h12.25c.525 0 .875-.35.875-.875v-8.75a.794.794 0 0 0-.262-.612l-3.5-3.5zM12 13H3v-3c0-.6.36-1 .9-1h7.2c.54 0 .9.4.9 1v3z" fill="#D6D8DD"/>
            <path class="path" d="M6 0h2v3H6z"/>
        </svg>

        <!-- 'arrow-down-strong': arrow down with additional line icon -->
        <svg v-if="name === 'arrow-down-strong'" viewBox="0 0 12 16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g class="path">
                <polygon points="7 16 14 8 10 8 10 4 4 4 4 8 0 8"/>
                <rect x="4" width="6" height="2"/>
            </g>
        </svg>

        <!-- 'loading': loader spinning icon -->
        <svg v-if="name === 'loading'" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <path class="path" d="M15 30C6.7 30 0 23.3 0 15c0-4.7 2.3-9.2 6-12l2.4 3.2C5.6 8.2 4 11.5 4 15c0 6 4.9 11 11 11s11-4.9 11-11c0-5.7-4.4-10.5-10-11l.3-4C24 .7 30 7.2 30 15c0 8.3-6.7 15-15 15z"/>
        </svg>

        <!-- 'funnel': funnel (filter) icon -->
        <svg v-if="name === 'funnel'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <path class="path" d="M15,0H1C0.4,0,0,0.4,0,1v2c0,0.3,0.1,0.6,0.4,0.8L6,8.5V15c0,0.4,0.2,0.8,0.6,0.9C6.7,16,6.9,16,7,16 c0.3,0,0.5-0.1,0.7-0.3l2-2C9.9,13.5,10,13.3,10,13V8.5l5.6-4.7C15.9,3.6,16,3.3,16,3V1C16,0.4,15.6,0,15,0z"></path>
        </svg>

        <!-- 'remove': trashcan icon -->
        <svg v-if="name === 'remove'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" fill-rule="evenodd" d="M12 4h3v2H1V4h3V2c0-.6.4-1 1-1h6c.6 0 1 .4 1 1v2zm-2 0V3H6v1h4z M13 7H3v7a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V7z"/>
        </svg>

        <svg v-if="name === 'plus'" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M7 0H5v5H0v2h5v5h2V7h5V5H7z"/>
        </svg>

        <svg v-if="name === 'plus-longer'" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
            <path class="stroke-path" stroke-width="2" d="M11 1v20M21 11H1"/>
        </svg>

        <svg v-if="name === 'plus-rounded'" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M11 5H7V1c0-.6-.4-1-1-1S5 .4 5 1v4H1c-.6 0-1 .4-1 1s.4 1 1 1h4v4c0 .6.4 1 1 1s1-.4 1-1V7h4c.6 0 1-.4 1-1s-.4-1-1-1z"/>
        </svg>

        <svg v-if="name === 'plus-circle'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm4 9H9v3H7V9H4V7h3V4h2v3h3v2z" fill-rule="nonzero"/>
        </svg>

        <svg v-if="name === 'plus-square'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 0H6v6H0v4h6v6h4v-6h6V6h-6z" fill="#444450" fill-rule="nonzero"/>
        </svg>

        <svg v-if="name === 'left-arrow'" viewBox="0 0 22 17" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M.293 7.707L8 0l1.414 1.414-6 6H21a1 1 0 1 1 0 2H3.414l6 6L8 16.828.293 9.121a.999.999 0 0 1 0-1.414z" fill-rule="nonzero"/>
        </svg>

        <svg v-if="name === 'right-arrow'" viewBox="0 0 22 17" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M21.707 7.707L14 0l-1.414 1.414 6 6H1a1 1 0 1 0 0 2h17.586l-6 6L14 16.828l7.707-7.707a.999.999 0 0 0 0-1.414z" fill-rule="nonzero"/>
        </svg>

        <svg v-if="name === 'search'" viewBox="0 0 16 17" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M12.7 11.3c.9-1.2 1.4-2.6 1.4-4.2 0-3.9-3.1-7.1-7-7.1S0 3.2 0 7.1c0 3.9 3.2 7.1 7.1 7.1 1.6 0 3.1-.5 4.2-1.4l3 3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4l-3-3.1zm-5.6.8c-2.8 0-5.1-2.2-5.1-5S4.3 2 7.1 2s5.1 2.3 5.1 5.1-2.3 5-5.1 5z" fill-rule="nonzero"/>
        </svg>

        <!-- 'clear': X sign, transparent and encircled -->
        <svg v-if="name === 'clear'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm3.5 10.1l-1.4 1.4L8 9.4l-2.1 2.1-1.4-1.4L6.6 8 4.5 5.9l1.4-1.4L8 6.6l2.1-2.1 1.4 1.4L9.4 8l2.1 2.1z" fill-rule="nonzero"/>
        </svg>

        <!-- 'clear-small': X sign, bold -->
        <svg v-if="name === 'x-bold'" viewBox="0 0 8 8" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M6.4 0L4 2.4 1.6 0 0 1.6 2.4 4 0 6.4 1.6 8 4 5.6 6.4 8 8 6.4 5.6 4 8 1.6z"/>
        </svg>

        <!-- 'close-bolder': X sign, thin -->
        <svg v-if="name === 'close-bolder'" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path class="path stroke-path" stroke-width="1" d="M13.4 12L23.7 1.7c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L12 10.6 1.7.3C1.3-.1.7-.1.3.3c-.4.4-.4 1 0 1.4L10.6 12 .3 22.3c-.4.4-.4 1 0 1.4.2.2.4.3.7.3.3 0 .5-.1.7-.3L12 13.4l10.3 10.3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4L13.4 12z" fill-rule="nonzero" />
        </svg>

        <!-- 'close': X sign, thin -->
        <svg v-if="name === 'close'" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M13.4 12L23.7 1.7c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L12 10.6 1.7.3C1.3-.1.7-.1.3.3c-.4.4-.4 1 0 1.4L10.6 12 .3 22.3c-.4.4-.4 1 0 1.4.2.2.4.3.7.3.3 0 .5-.1.7-.3L12 13.4l10.3 10.3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4L13.4 12z" fill-rule="nonzero" />
        </svg>

        <svg v-if="name === 'close-rounded'" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M9.786.214a.69.69 0 0 0-1 0L5 4 1.214.214a.69.69 0 0 0-1 0 .69.69 0 0 0 0 1L4 5 .214 8.786a.69.69 0 0 0 0 1 .648.648 0 0 0 .5.214.648.648 0 0 0 .5-.214L5 6l3.786 3.786a.772.772 0 0 0 .5.214.772.772 0 0 0 .5-.214.69.69 0 0 0 0-1L6 5l3.786-3.786a.69.69 0 0 0 0-1z"/>
        </svg>

        <!-- 'mail': envelope icon -->
        <svg v-if="name === 'mail'" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M15 0H1C.4 0 0 .4 0 1v1.4l8 4.5 8-4.4V1c0-.6-.4-1-1-1z"/>
            <path class="path" d="M7.5 8.9L0 4.7V13c0 .6.4 1 1 1h14c.6 0 1-.4 1-1V4.7L8.5 8.9c-.28.14-.72.14-1 0z"/>
        </svg>

        <!-- 'screen-donwload': dekstop screen with a down arrow in the right side -->
        <svg v-if="name === 'screen-download'" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M13 5h2V2c0-1.065-.935-2-2-2H3C1.935 0 1 .935 1 2v5h2V2h10v3zM7 9H0v1a2 2 0 0 0 2 2h5V9zM14 10.586l-1 1V7h-2v4.586l-1-1L8.586 12l2.707 2.707a.997.997 0 0 0 1.414 0L15.414 12 14 10.586z"/>
        </svg>

        <!-- 'card-edit': credit card with pencil overlay -->
        <svg v-if="name === 'card-edit'" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M12 14l-3 1 1-3 4-4 2 2zM2 6h14V1a1 1 0 0 0-1-1H1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h6v-2H2V6zm0-4h12v2H2V2z"/>
        </svg>

        <!-- 'chain-link': single link in the chain -->
        <svg v-if="name === 'chain-link'" viewBox="0 0 18 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M5.5 16c-1.2 0-2.3-.5-3.2-1.3-1.8-1.8-1.8-4.6 0-6.4l.7-.7L4.4 9l-.7.7c-1 1-1 2.6 0 3.6s2.6 1 3.6 0l3-3c1-1 1-2.6 0-3.6L9.6 6 11 4.6l.7.7c1.8 1.8 1.8 4.6 0 6.4l-3 3c-.8.8-2 1.3-3.2 1.3z"/><path class="path" d="M7 11.4l-.7-.7c-1.8-1.8-1.8-4.6 0-6.4l3-3c.9-.9 2-1.3 3.2-1.3 1.2 0 2.3.5 3.2 1.3 1.8 1.8 1.8 4.6 0 6.4l-.7.7L13.6 7l.7-.7c1-1 1-2.6 0-3.6s-2.6-1-3.6 0l-3 3c-1 1-1 2.6 0 3.6l.7.7L7 11.4z"/>
        </svg>

        <!-- 'clock': default clock - 3 o'clock -->
        <svg v-if="name === 'clock'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 14c-3.3 0-6-2.7-6-6s2.7-6 6-6 6 2.7 6 6-2.7 6-6 6z"/>
            <path class="path" d="M9 4H7v5h5V7H9z"/>
        </svg>

        <!-- 'sort': sort arrow icon -->
        <svg v-if="name === 'sort'" viewBox="0 0 7 9" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd">
                <path class="path" fill="#000" d="M3 0h1v8H3z"/>
                <g fill="#222235">
                    <path class="path" d="M3 8h1v1H3zM2 7h1v1H2zM1 6h1v1H1zM0 5h1v1H0zM4 7h1v1H4zM5 6h1v1H5zM6 5h1v1H6z"/>
                </g>
            </g>
        </svg>

        <svg v-if="name === 'backward'" viewBox="0 0 30 22" xmlns="http://www.w3.org/2000/svg">
            <g fill-rule="evenodd">
                <path class="stroke-path" stroke="#D6D8DD" stroke-width="2" fill="none" stroke-linecap="square" d="M11 20l-9-9 9-9"/>
            </g>
        </svg>

        <svg v-if="name === 'pencil-edit-line'" viewBox="0 0 23 26" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M18.451 20.996H3.91c-.545 0-.909-.364-.909-.91 0-.545.364-.908.909-.908H18.45c.545 0 .909.363.909.909 0 .545-.364.909-.909.909zM17.27 7.635l1.454-1.454a.879.879 0 0 0 0-1.273l-3.272-3.272a.879.879 0 0 0-1.273 0L12.725 3.09l4.545 4.545zM11.453 4.363l-7.271 7.271a.825.825 0 0 0-.273.636v3.272c0 .546.363.91.909.91H8.09c.272 0 .454-.092.636-.274l7.271-7.27-4.544-4.545z"/>
        </svg>

        <svg v-if="name === 'alpha'" viewBox="0 0 300 92" xmlns="http://www.w3.org/2000/svg">
            <path fill="#FE664E" d="M0 0h300v92H0z"/>
            <path d="M73.315 71.077l-3.697-11.642h-17.04L48.88 71.077H38.463l16.838-48h11.932l16.872 48h-10.79zM60.98 32.257l-6.218 19.66h12.67l-6.216-19.66h-.236zm60.987 30.504H100.76V23.078H90.61v48h31.357V62.76zm7.58-39.683h20.234c9.95 0 16.806 6.553 16.806 16.432 0 9.846-7.09 16.432-17.31 16.432H139.7v15.135h-10.15v-48zm10.15 7.95V48.09h7.362c5.813 0 9.208-3.06 9.208-8.547 0-5.456-3.36-8.516-9.176-8.516h-7.394zm76.583 40.05v-48h-10.15V42.57h-21.846V23.077h-10.15v48h10.15V50.852h21.846v20.225h10.15zm41.393 0l-3.697-11.642h-17.04l-3.698 11.642h-10.42l16.84-48h11.93l16.874 48h-10.79zm-12.335-38.82l-6.218 19.66h12.67l-6.217-19.66h-.235z" fill="#FFF"/>
        </svg>

        <svg v-if="name === 'beta'" viewBox="0 0 300 114" xmlns="http://www.w3.org/2000/svg">
            <path fill="#FE664E" d="M0 0h300v114H0z"/>
            <path d="M80.192 86.006h-27.01v-60h26.552c11.296 0 18.382 5.78 18.382 14.97 0 6.57-4.918 12.057-11.296 13.014v.332c8.17.624 14.173 6.57 14.173 14.512 0 10.56-7.962 17.172-20.8 17.172zM65.77 35.362v15.592h9.42c6.71 0 10.546-2.952 10.546-8.025 0-4.824-3.376-7.568-9.254-7.568H65.77zm0 41.29h11.213c7.253 0 11.17-3.078 11.17-8.816 0-5.614-4.042-8.607-11.462-8.607H65.77v17.42zm84.183-1.04H122.69V60.475h25.72V50.83h-25.72V36.36h27.263V26.006h-39.85v60h39.85V75.61zm38.205 10.394H175.57V36.36h-17.59V26.006h47.81V36.36H188.16v49.646zm57.797 0l-4.585-14.553h-21.134l-4.585 14.553h-12.92l20.883-60h14.797l20.926 60h-13.38zm-15.298-48.524l-7.71 24.574h15.714l-7.71-24.574h-.293z" fill="#FFF"/>
        </svg>

        <svg v-if="name === 'caret'" viewBox="0 0 12 5" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M6 5l6-5H0z"/>
        </svg>

        <svg v-if="name === 'list-search'" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M4 12H1a1 1 0 0 1 0-2h3v2zM15 2H1a1 1 0 0 1 0-2h14a1 1 0 0 1 0 2zM4 7H1a1 1 0 0 1 0-2h3v2zM15.707 12.293l-2.274-2.274A3.947 3.947 0 0 0 14 8c0-2.206-1.794-4-4-4S6 5.794 6 8s1.794 4 4 4c.74 0 1.424-.215 2.019-.567l2.274 2.274 1.414-1.414zM10 10c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"/>
        </svg>

        <svg v-if="name === 'bars'" viewBox="0 0 7 10" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M1 0v10H0V0h1zm3 0v10H3V0h1zm3 0v10H6V0h1z"/>
        </svg>

        <svg v-if="name === 'check'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path class="path" d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/>
        </svg>

        <svg v-if="name === 'check-rounded'" viewBox="0 0 12 10" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M10.725.458L3.75 7.433 1.275 4.958c-.3-.3-.75-.3-1.05 0-.3.3-.3.75 0 1.05l3 3c.15.15.3.225.525.225a.68.68 0 0 0 .525-.225l7.5-7.5c.3-.3.3-.75 0-1.05-.3-.3-.75-.3-1.05 0z"/>
        </svg>

        <svg v-if="name === 'check-circle'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M16 8c0-4.411-3.589-8-8-8S0 3.589 0 8s3.589 8 8 8 8-3.589 8-8zM4 8.414l3.414 3.414 5.414-5.414L11.414 5l-4 4-2-2L4 8.414z" fill-rule="evenodd"/>
        </svg>

        <svg v-if="name === 'minus'" viewBox="0 0 12 2" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M11 0H1C.4 0 0 .4 0 1s.4 1 1 1h10c.6 0 1-.4 1-1s-.4-1-1-1z"/>
        </svg>

        <svg v-if="name === 'update'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 16">
            <path class="path" d="M7.5 3c1.2 0 2.2.4 3.2 1H8v2h6V0h-2v2.5C10.7 1.5 9.2 1 7.5 1 3.6 1 .3 4 0 7.9l2 .2C2.2 5.2 4.6 3 7.5 3zM6.5 13c-1.2 0-2.2-.4-3.2-1H6v-2H0v6h2v-2.5c1.3 1 2.8 1.5 4.5 1.5 3.9 0 7.2-3 7.5-6.9l-2-.2c-.2 2.9-2.6 5.1-5.5 5.1z"/>
        </svg>

        <svg v-if="name === 'plus-thin'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 16">
            <g fill="none" fill-rule="evenodd">
                <path class="path" d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" fill="#D6D8DD" fill-rule="nonzero"/>
            </g>
        </svg>

        <svg v-if="name === 'upload'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 30">
            <path class="path" d="M7.06 22.94h10.587V12.354h7.06L12.352 0 0 12.353h7.06V22.94zM0 26.47h24.706V30H0v-3.53z"/>
        </svg>

        <svg v-if="name === 'database'" viewBox="0 0 54 62" xmlns="http://www.w3.org/2000/svg">
            <g fill-rule="evenodd">
                <path class="path" d="M27 0C13.9 0 0 3.2 0 9s13.9 9 27 9 27-3.2 27-9-13.9-9-27-9z"/>
                <path class="path" d="M46 17.6C40.9 19.1 34 20 27 20s-13.9-.9-19-2.4c-4.1-1.2-6.5-2.7-8-4.2V31c0 5.8 13.9 9 27 9s27-3.2 27-9V13.4c-1.5 1.5-4 3-8 4.2z"/>
                <path class="path" d="M46 39.6C40.9 41.1 34 42 27 42s-13.9-.9-19-2.4c-4.1-1.2-6.5-2.7-8-4.2V53c0 5.8 13.9 9 27 9s27-3.2 27-9V35.4c-1.5 1.5-4 3-8 4.2z"/>
            </g>
        </svg>

        <svg v-if="name === 'comment'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M15 0H1C.4 0 0 .4 0 1v10c0 .6.4 1 1 1h5l6 4v-4h3c.6 0 1-.4 1-1V1c0-.6-.4-1-1-1z" fill-rule="nonzero"/>
        </svg>

        <svg v-if="name === 'dots-vertical'" viewBox="0 0 4 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M4,2c0,1.1-0.9,2-2,2S0,3.1,0,2s0.9-2,2-2S4,0.9,4,2z M2,6C0.9,6,0,6.9,0,8s0.9,2,2,2s2-0.9,2-2S3.1,6,2,6z M2,12c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S3.1,12,2,12z"/>
        </svg>

        <svg v-if="name === 'arrow-up'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M8 3L15.2071 10.2071L13.7929 11.6213L8 5.82843L2.20711 11.6213L0.792892 10.2071L8 3Z"/>
        </svg>

        <svg v-if="name === 'warning'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M15.216 13.529L8.882 1.654a1 1 0 0 0-1.765 0L.784 13.529A1 1 0 0 0 1.667 15h12.667a1 1 0 0 0 .882-1.471zM8 13a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm1-3H7V6h2v4z"/>
        </svg>

        <svg v-if="name === 'preview'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M16 1a1 1 0 0 0-1-1H1a1 1 0 0 0-1 1v10.8a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V1zM2.2 6.6c3.3-5.333 8.7-5.333 12 0-3.3 5.333-8.7 5.333-12 0zm6-2c-1.12 0-2 .88-2 2s.88 2 2 2 2-.88 2-2-.88-2-2-2zM1.6 16h12.8v-1.6H1.6V16z" />
        </svg>

        <svg v-if="name === 'unlinked'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none">
            <path fill="#000" d="M15 3h-5c-.6 0-1 .4-1 1s.4 1 1 1h4v6c-.6 0-1 .4-1 1s.4 1 1 1h1c.6 0 1-.4 1-1V4c0-.6-.4-1-1-1zM6 11H2V5c.6 0 1-.4 1-1s-.4-1-1-1H1c-.6 0-1 .4-1 1v8c0 .6.4 1 1 1h5c.6 0 1-.4 1-1s-.4-1-1-1z"/>
            <path fill="#000" d="M12 7H4v2h8V7zM5.7 6H8L4.9.5C4.6 0 4-.1 3.5.1c-.5.2-.6.9-.4 1.4L5.7 6zM10.3 10H8l3.1 5.5c.2.3.5.5.9.5.2 0 .3 0 .5-.1.5-.3.6-.9.4-1.4L10.3 10z"/>
        </svg>

        <svg v-if="name === 'user-group'" viewBox="0 0 18 12" xmlns="http://www.w3.org/2000/svg">
            <path class="path" opacity="0.8" d="M10 10.545C10 9.79 9.70556 9.07125 9.18 8.6025C8.37667 7.885 6.98333 7 5 7C3.01667 7 1.62333 7.885 0.82 8.6025C0.294444 9.07125 0 9.79 0 10.545V12H10V10.545Z"/>
            <path class="path" opacity="0.8" d="M5 6C6.65685 6 8 4.65685 8 3C8 1.34315 6.65685 0 5 0C3.34315 0 2 1.34315 2 3C2 4.65685 3.34315 6 5 6Z"/>
            <path class="path" opacity="0.8" d="M13 6C14.6569 6 16 4.65685 16 3C16 1.34315 14.6569 0 13 0C11.3431 0 10 1.34315 10 3C10 4.65685 11.3431 6 13 6Z"/>
            <path class="path" opacity="0.8" d="M17.1417 8.6025C16.3009 7.885 14.8425 7 12.7666 7C12.1141 7 11.5303 7.09375 11 7.23625C11.749 8.13625 12.1851 9.295 12.1851 10.545V12H18V10.545C18 9.79 17.6918 9.07125 17.1417 8.6025Z"/>
        </svg>

        <svg v-if="name === 'preview-new'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" fill-rule="evenodd" d="M0 14H16V2H0V14ZM2 15V16H14V15H2ZM2 8C3.65201 10.6699 6 12 8 12C10 12 12.352 10.6634 14 8C12.3511 5.33506 10 4 8 4C6 4 3.65107 5.33161 2 8ZM6 8C6 9.12 6.88 10 8 10C9.12 10 10 9.12 10 8C10 6.88 9.12 6 8 6C6.88 6 6 6.88 6 8Z"/>
        </svg>

        <svg v-if="name === 'close-v2'" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="path" fill-rule="evenodd" clip-rule="evenodd" d="M13.7.3c-.4-.4-1-.4-1.4 0L7 5.6 1.7.3C1.3-.1.7-.1.3.3c-.4.4-.4 1 0 1.4L5.6 7 .3 12.3c-.4.4-.4 1 0 1.4.2.2.4.3.7.3.3 0 .5-.1.7-.3L7 8.4l5.3 5.3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4L8.4 7l5.3-5.3c.4-.4.4-1 0-1.4z"/>
        </svg>

        <svg v-if="name === 'check-thin'" viewBox="0 0 16 12" xmlns="http://www.w3.org/2000/svg">
            <path class="path" fill-rule="evenodd" clip-rule="evenodd" d="M14.3.3L5 9.6 1.7 6.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l4 4c.2.2.4.3.7.3.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0z"/>
        </svg>

        <svg v-if="name === 'external-link'" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M14.6125 1H8.4875V2.75H12.5125L7 8.2625L8.225 9.4875L13.7375 3.975V8H15.4875V1.875C15.4875 1.35 15.1375 1 14.6125 1Z" fill="#444450"/>
            <path class="path" d="M13.25 15.125H1.875C1.35 15.125 1 14.775 1 14.25V2.875C1 2.35 1.35 2 1.875 2H5.375V3.75H2.75V13.375H12.375V10.75H14.125V14.25C14.125 14.775 13.775 15.125 13.25 15.125Z" fill="#525266"/>
        </svg>

        <svg v-if="name === 'error-triangle'" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="path" opacity=".8" fill-rule="evenodd" clip-rule="evenodd" d="M14.55 12.405L8.218.53a1 1 0 0 0-1.765 0L.119 12.404a1 1 0 0 0 .883 1.472h12.667a1 1 0 0 0 .882-1.471zm-7.215-.53a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm-1-3h2v-4h-2v4z"/>
        </svg>

        <svg v-if="name === 'dropdown-arrow'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M4 6h8l-4 4-4-4z"/>
        </svg>

        <!-- two arrows made into a circle -->
        <svg v-if="name === 'refresh'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M4.576 3.818c2.022-2.015 5.428-2.015 7.449 0 .745.743 1.277 1.804 1.49 2.863l2.128-.318c-.213-1.59-.957-2.97-2.021-4.03-2.873-2.863-7.556-2.863-10.536 0L.746 0 0 6.788l6.81-.743-2.234-2.227zM16 9.212l-6.812.743 2.235 2.227c-2.021 2.015-5.427 2.015-7.449 0-.744-.743-1.277-1.804-1.49-2.863l-2.127.318c.212 1.59.957 2.968 2.021 4.029 1.49 1.486 3.299 2.121 5.214 2.121 1.916 0 3.831-.742 5.215-2.121L15.149 16l.85-6.788z"/>
        </svg>

        <svg v-if="name === 'strategy-report'" xmlns="http://www.w3.org/2000/svg" width="16" height="16">
            <path class="path" d="M7 0.0618896C3.05369 0.553988 0 3.92038 0 8C0 12.4183 3.58172 16 8 16C10.5191 16 12.7662 14.8357 14.2326 13.0159L7.66899 8.94391C7.28524 8.70788 7 8.5 7 8V0.0618896Z" />
            <path class="path" d="M15.2825 11.3161L9 7.45193V0.0618897C12.9463 0.553988 16 3.92038 16 8C16 9.1829 15.7433 10.3058 15.2825 11.3161Z" />
        </svg>

        <svg v-if="name === 'support'" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd">
                <circle cx="8.5" cy="8.5" r="8.5" fill="#D8D8D8"/>
                <path fill="#1F1F2C" d="M6 6.505h1.746c.016-.52.37-.864.886-.864.505 0 .86.3.86.737 0 .473-.19.7-.92 1.128-.746.43-1.014.913-.912 1.832l.016.215h1.676V9.3c0-.505.204-.746.94-1.17.79-.457 1.16-1.005 1.16-1.8 0-1.29-1.064-2.148-2.697-2.148-1.718 0-2.74.918-2.755 2.325zm2.562 5.683c.693 0 1.096-.36 1.096-.978 0-.623-.403-.982-1.096-.982-.693 0-1.1.36-1.1.982 0 .618.407.978 1.1.978z"/>
            </g>
        </svg>

        <svg v-if="name === 'kebab'" viewBox="0 0 4 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M2 10c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0-6C.9 4 0 3.1 0 2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0 12c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
        </svg>

        <svg v-if="name === 'falafels-icon-light'" id="falafels-icon-light" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
            <rect class="background" width="24" height="24" rx="4"/>
            <path class="path balls" d="M12 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0-5c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0 10c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" />
        </svg>

        <svg v-if="name === 'location'" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" fill-rule="evenodd" d="M6.02 13.468C6.818 14.394 7.547 15.243 8 16c.454-.757 1.183-1.606 1.98-2.532C11.811 11.335 14 8.788 14 6c0-3.3-2.7-6-6-6S2 2.7 2 6c0 2.789 2.188 5.335 4.02 7.468zM6 6c0 1.1.9 2 2 2s2-.9 2-2-.9-2-2-2-2 .9-2 2z"/>
        </svg>

        <svg v-if="name === 'time'" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" fill-rule="evenodd" d="M16 8c0-4.4-3.6-8-8-8S0 3.6 0 8s3.6 8 8 8 8-3.6 8-8zM9 7V4H7v5h5V7H9z"/>
        </svg>

        <svg v-if="name ==='add-ball'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="plus" d="M3 2h10v11H3z"/>
            <path class="path circle" d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm4 9H9v3H7V9H4V7h3V4h2v3h3v2z" />
        </svg>

        <svg v-if="name === 'creative'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M15 0H1C.4 0 0 .4 0 1v14c0 .6.4 1 1 1h14c.6 0 1-.4 1-1V1c0-.6-.4-1-1-1zM6 4c.6 0 1 .4 1 1s-.4 1-1 1-1-.4-1-1 .4-1 1-1zm-3 8l2-4 2 2 3-4 3 6H3z" />
        </svg>

        <svg v-if="name === 'audience'" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M10.2 11.4l-2.7-2C8.4 8.7 9 7.7 9 6.5v-.8C9 3.8 7.6 2.1 5.7 2 3.7 1.9 2 3.5 2 5.5v1c0 1.2.6 2.2 1.5 2.9L.8 11.5c-.5.4-.8 1-.8 1.6V15c0 .6.4 1 1 1h9c.6 0 1-.4 1-1v-2c0-.6-.3-1.2-.8-1.6zM15.1 6.4l-1.8-1.2c.4-.4.7-1 .7-1.7v-.9C14 1.4 12.6 0 11.5 0S9.7.5 9.2 1.4c1.1 1 1.8 2.4 1.8 4v1c0 .9-.2 1.8-.6 2.5 0 0 1.2.9 1.2 1H15c.6 0 1-.4 1-1v-.8c0-.7-.3-1.3-.9-1.7z"/>
        </svg>

        <svg v-if="name === 'language'" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M7.548 9.55l.634-1.805a8.59 8.59 0 0 1-1.875-.828A7.423 7.423 0 0 0 7.416 5.3c.311-.611.541-1.26.684-1.931.589 0 .979.02 1.419.06V1.62c-.482.052-.966.075-1.45.07H5.687v-.61c0-.205.02-.41.06-.61H3.738c.041.204.061.412.06.62v.6H1.589A12.32 12.32 0 0 1 .14 1.62v1.809c.5-.04.839-.06 1.429-.06a8.8 8.8 0 0 0 .69 1.709 8.7 8.7 0 0 0 1.289 1.829A10.892 10.892 0 0 1 0 8.216c.42.53.745 1.128.959 1.769a12.816 12.816 0 0 0 3.979-1.839 11.58 11.58 0 0 0 2.61 1.404zm-4.06-6.181h2.649a5.635 5.635 0 0 1-1.25 2.418 5.7 5.7 0 0 1-1.399-2.418zM13.717 16l-.725-2.379H9.349L8.624 16H6.341L9.868 5.965h2.591L16 16h-2.283zm-1.231-4.156l-1.131-3.657a10.295 10.295 0 0 1-.181-.67c-.15.583-.581 2.025-1.292 4.327h2.604z" />
        </svg>

        <svg v-if="name === 'creativeLiftStudy'" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M4 7c1.7 0 3-1.3 3-3S5.7 1 4 1 1 2.3 1 4s1.3 3 3 3zm0-4c.6 0 1 .4 1 1s-.4 1-1 1-1-.4-1-1 .4-1 1-1zM12 9c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm0 4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z M15 1H9v2h2.6L1.3 13.3l1.4 1.4L13 4.4V7h2V1z" />
        </svg>

        <svg v-if="name === 'splitTest'" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
            <path class="path" d="M13 1.5c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zM3 8.5c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z M3 1.5L6 7H0l3-5.5zM13 14.5L16 9h-6l3 5.5zM7 1h2v14H7z"/>
        </svg>
    </div>
</template>

<script>
export default {
    props: {
        name: { type: String, required: true },
    },
}
</script>

<style lang="less" scoped>
@import (reference) '~design-system/less/common';

.icon-wrapper {
    font-size: 0;
    display: inline-block;
    width: 16px;
    height: 16px;
}

.icon-wrapper > svg {
    width: 100%;
    height: 100%;
}

.path {
    fill: currentColor;
}

.stroke-path {
    stroke: currentColor;
}

.relative-wrap {
    position: relative;
}

.spin {
    animation: SPIN 1s ease-out infinite;
}

@keyframes SPIN {
    0% { transform: rotate(0); }
    100% { transform: rotate(720deg); }
}

#falafels-icon-light {
    height: 24px;
    width: 24px;

    .background {
        fill: @void;
        opacity: 0;
        transition: @default-transition-time ease;
    }
    .balls {
        transition: @default-transition-time ease;
    }

    &:hover {
        .background {
            opacity: .06;
        }
    }

    &:active {
        .background {
            opacity: .12;
        }
    }
}
</style>
