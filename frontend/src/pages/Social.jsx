import { useLoaderData } from "react-router-dom";

import SocialTerminal from "../features/social/SocialTerminal";
import { getAboutMe } from "../services/apiAboutMe";

function Social() {
    const { aboutMe } = useLoaderData();

    return (
        <main className="flex min-h-screen items-center justify-center bg-[#05070a] px-4 py-6 sm:px-6 md:py-10">
            <SocialTerminal aboutMe={aboutMe} />
        </main>
    );
}

export default Social;

// eslint-disable-next-line
export async function loader({ request }) {
    const { signal } = request;

    const aboutMe = await getAboutMe({ signal });

    return { aboutMe };
}