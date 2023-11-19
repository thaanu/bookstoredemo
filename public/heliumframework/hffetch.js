async function hfGetRequest(url) {
    const response = await fetch(url);
    const json = await response.json();
    return json;
}
async function hfPostRequest(url, dataset = {}) {
    const response = await fetch(url, {
        method: "POST",
        mode: "same-origin",
        cache: "no-cache",
        credentials: "same-origin",
        headers: {
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: "follow",
        referrerPolicy: "no-referrer",
        body: dataset
    });
    const json = await response.json();
    return json;
}