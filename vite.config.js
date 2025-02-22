import sharp from 'vite-plugin-sharp';

export default {
    build: {
        // Directorio de salida.
        outDir: 'public/dist',

        // Evita copiar archivos que no son assets.
        copyPublicDir: false,

        // Puntos de entrada.
        rollupOptions: {
            input: {
                app: './assets/js/app.js',          // JS principal.
                styles: './assets/css/app.css',     // CSS principal.
                images: './assets/js/images.js'     // Imágenes.
            },
            output: {
                // Nombres de archivos de salida.
                entryFileNames: 'js/[name].min.js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/[name].min.css';
                      }

                      // Usamos originalFileNames para mantener la estructura
                      if (assetInfo.originalFileNames && assetInfo.originalFileNames[0]) {
                        // Removemos 'assets/' del inicio y mantenemos el resto de la ruta
                        return assetInfo.originalFileNames[0].replace('assets/', '');
                      }

                      return assetInfo.name;
                }
            }
        }
    },
    plugins: [
        sharp({
            // Configuración general.
            force: true, // Procesa todas las imágenes.

            // Configuración por tipo de imagen.
            png: {
                quality: 85,
                compressionLevel: 9 // 0-9, mayor = más compresión.
            },
            jpeg: {
                quality: 85,
                progressive: true
            },

            // Opcional: redimensionar todas las imágenes grandes.
            resize: {
                width: 2000,  // Máximo ancho.
                height: 2000, // Máximo alto.
                fit: 'inside' // Mantiene proporción.
            }
        })
    ],
}
