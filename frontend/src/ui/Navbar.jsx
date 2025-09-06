import { HiMiniBars3 } from "react-icons/hi2";
import { Link } from "react-router-dom";
import NavbarMobile from "./NavbarMobile";
import ToggleTheme from "./ToggleTheme";
import { useState } from "react";
import NavbarItem from "./NavbarItem";
import PropTypes from "prop-types";

Navbar.propTypes = {
    navbarItems: PropTypes.array.isRequired,
};

function Navbar({ navbarItems }) {
    const [isOpen, setIsOpen] = useState(false);

    function handleToggle() {
        setIsOpen((state) => !state);
    }

    return (
        <>
            <nav className="fixed top-0 left-0 w-full flex justify-between items-center px-6 py-4 bg-secondary-light dark:bg-secondary-dark z-50">
                <div className="flex justify-end items-center gap-6">
                    <ul className="hidden md:flex items-center gap-6 m-0 p-0 list-none">
                        {navbarItems?.map((item) => (
                            <NavbarItem
                                to={item.link}
                                label={item.label}
                                key={`item-${item.id}`}
                                className="text-dark dark:text-light hover:text-primary dark:hover:text-primary"
                            />
                        ))}
                    </ul>
                    <button
                        aria-label="Open mobile menu"
                        title="Open mobile menu"
                        onClick={handleToggle}
                        className="md:hidden text-primary dark:text-white text-2xl"
                    >
                        <HiMiniBars3 />
                    </button>
                    <ToggleTheme />
                </div>
                <Link
                    to="/#hero"
                    className="font-bold text-lg text-primary dark:text-white"
                >
                    MyPortfolio
                </Link>
                <NavbarMobile
                    navbarItems={navbarItems}
                    isOpen={isOpen}
                    onToggle={handleToggle}
                />
            </nav>
        </>
    );
}

export default Navbar;
