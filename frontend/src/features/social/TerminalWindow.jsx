import PropTypes from "prop-types";

TerminalWindow.propTypes = {
    children: PropTypes.node.isRequired,
    contentRef: PropTypes.shape({
        current: PropTypes.instanceOf(Element),
    }),
};

function TerminalWindow({ children, contentRef = null }) {
    return (
        <div className="flex h-[min(720px,82vh)] w-full max-w-5xl flex-col overflow-hidden rounded-xl border border-white/10 bg-[#0b0f14] shadow-[0_30px_100px_rgba(0,0,0,0.55)]">
            <header className="relative flex h-11 shrink-0 items-center border-b border-white/10 bg-[#171b21] px-4">
                <div aria-hidden="true" className="flex gap-2">
                    <span className="size-3 rounded-full bg-[#ff5f57]" />
                    <span className="size-3 rounded-full bg-[#febc2e]" />
                    <span className="size-3 rounded-full bg-[#28c840]" />
                </div>

                <p className="pointer-events-none absolute left-1/2 -translate-x-1/2 text-xs text-white/45">
                    amirsaeed — social — zsh
                </p>
            </header>

            <div ref={contentRef} className="flex-1 overflow-y-auto scroll-smooth p-5 md:p-7">
                {children}
            </div>
        </div>
    );
}

export default TerminalWindow;
