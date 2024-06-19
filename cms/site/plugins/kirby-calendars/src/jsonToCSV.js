export function jsonToCsv(jsonData) {
    if (jsonData.length === 0) {
        return '';
    }

    const headers = Object.keys(jsonData[0]).reduce((acc, key) => {
        if (typeof jsonData[0][key] === 'object') {
            Object.keys(jsonData[0][key]).forEach(subKey => {
                acc.push(`${key}.${subKey}`);
            });
        } else {
            acc.push(key);
        }
        return acc;
    }, []);

    // Extract rows
    const csvRows = jsonData.map(row => {
        return headers.map(header => {
            const [mainKey, subKey] = header.split('.');
            let value = row[mainKey];
            if (subKey && typeof value === 'object') {
                value = value[subKey];
            }
            return typeof value === 'string' ? `"${value.replace(/"/g, '""')}"` : value;
        }).join(',');
    });

    return [headers.join(','), ...csvRows].join('\n');
}
