import { Outlet, ScrollRestoration, useLoaderData } from "react-router-dom";
import Navbar from "./Navbar";
import Footer from "./Footer";
import ScrollToHash from "./ScrollToHash";
import { getAboutMe } from "../services/apiAboutMe";
import { getNavbarItems } from "../services/apiNavbarItem";
import { Suspense, useRef } from "react";
import ScrollToTop from "./ScrollToTop";

function AppLayout() {
    const { aboutMe, navbar } = useLoaderData();
    const mainRef = useRef(null);
    return (
        <>
            <ScrollToHash />
            <Navbar navbarItems={navbar} />
            <main ref={mainRef}>
                <Suspense
                    fallback={
                        <p className="text-yellow-400 z-[1000]">Loading ...</p>
                    }
                >
                    <Outlet context={{ aboutMe }} />
                </Suspense>
            </main>
            <ScrollRestoration />
            <ScrollToTop target={mainRef} />
            <Footer aboutMe={aboutMe} />
        </>
    );
}

export default AppLayout;

// eslint-disable-next-line
export async function loader({ request }) {
    const { signal } = request;
    const navbar = await getNavbarItems({ signal });
    const aboutMe = await getAboutMe({ signal });
    return { aboutMe, navbar };
}
