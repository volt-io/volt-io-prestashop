export interface PatchData {
    amount: number;
}

export interface PatchResponse {
    id: string;
    token: string;
}

export async function patchData(url: string, data: PatchData): Promise<PatchResponse> {
    try {
        const response = await fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        console.log(response);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const responseData: PatchResponse = await response.json();
        return responseData;
    } catch (error) {
        console.error("Failed to patch data: ", error);
        throw new Error("Failed to patch data");
    }
}
