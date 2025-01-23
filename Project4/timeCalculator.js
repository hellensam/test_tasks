function timeSince (unixTime) {
    const currentDate = Date.now();
    const diffMs = currentDate - unixTime * 1000;
    const minutes = Math.floor(diffMs / 60000);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);

    const formatDate = (timestamp) => {
        const date = new Date(timestamp * 1000);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        return `${day}.${month}.${year}`;
    };

    const roundedMinutes = (min) => Math.round(min / 5) * 5;

    const ranges = [
        {check: () => minutes <= 5, result: () => 'just now'},
        {check: () => minutes <= 60, result: () => `${minutes} minutes ago`},
        {check: () => hours < 8, result: () => {
                const remainingMinutes = roundedMinutes(minutes % 60);

                return `${hours} hours${remainingMinutes ? ` ${remainingMinutes} minutes` : ''} ago`;
        }},
        {check: () => hours < 24, result: () => {
                const roundedHours = Math.round(minutes / 60);

                return `${roundedHours} hours ago`;
        }},
        {check: () => days < 30, result: () => `${days} days ago`},
        {check: () => true, result: () => formatDate(unixTime)}
    ];

    return ranges.find(range => range.check()).result();
}

console.log(timeSince(1737575794));
