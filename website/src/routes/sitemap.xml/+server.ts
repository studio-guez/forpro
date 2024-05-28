import { getItems } from '$lib/data'; // Fonction pour récupérer les données dynamiques

export async function GET() {
    const items = (await getItems()).nav;

    //todo: <lastmod>${item.lastModified}</lastmod>
    const sitemap = `
    <?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
      ${items.filter((value) => value.showmenu).map(item => `
        <url>
          <loc>${item.url}</loc>
        </url>
      `).join('')}
    </urlset>`.trim(); // Trim to remove any accidental whitespace

    return new Response(sitemap, {
        headers: {
            'Content-Type': 'application/xml'
        }
    });
}
