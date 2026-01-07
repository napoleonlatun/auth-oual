<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTH_OUALFAM // SYSTEM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'JetBrains Mono', monospace; }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-[#050505] text-white min-h-screen flex flex-col items-center py-20 selection:bg-white selection:text-black relative overflow-x-hidden">

    <!-- Background Glow -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-white opacity-[0.03] blur-[120px] rounded-full pointer-events-none"></div>

    <!-- HEADER -->
    <div class="z-10 text-center mb-12">
        <p class="text-xs text-gray-500 tracking-[0.2em] mb-3 uppercase">Secure Environment v1.0</p>
        <h1 class="text-5xl font-bold tracking-tighter bg-gradient-to-b from-white to-gray-500 text-transparent bg-clip-text">
            AUTH_OUALFAM
        </h1>
    </div>

    <!-- LOGGED IN DASHBOARD -->
    @auth
        <!-- Control panel -->
        <div class="glass z-10 p-8 rounded-2xl w-[500px] text-center shadow-2xl relative mb-8">
            <div class="flex justify-between items-center mb-6 border-b border-white/10 pb-4">
                <div class="text-left">
                    <h2 class="text-lg text-white">Cmdr. {{ auth()->user()->name }}</h2>
                    <p class="text-[10px] text-emerald-500 uppercase tracking-widest">● System Online</p>
                </div>
                
                <form action="/logout" method="POST">
                    @csrf
                    <button class="text-[10px] uppercase tracking-widest text-gray-500 hover:text-white transition">
                        [ Terminate ]
                    </button>
                </form>
            </div>

            <!-- Post form -->
            <form action="/create-post" method="POST" class="text-left">
                @csrf
                <label class="text-[10px] text-gray-500 uppercase tracking-widest mb-2 block">New Transmission</label>
                <div class="relative">
                    <textarea name="content" rows="2" required placeholder="Type command..."
                        class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm text-gray-300 focus:outline-none focus:border-white/30 transition resize-none"></textarea>
                    
                    <button class="absolute bottom-3 right-3 text-[10px] bg-white text-black px-3 py-1 rounded font-bold uppercase hover:bg-gray-200 transition">
                        Send
                    </button>
                </div>
            </form>
        </div>

        <!-- FEED -->
        <div class="w-[500px] space-y-4 z-10">
            @foreach($posts as $post)
    <div class="glass p-5 rounded-xl ...">
        <div class="flex justify-between items-start mb-2">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                    // {{ optional($post->user)->name ?? 'Unknown' }}
                </span>

                @if(auth()->check() && $post->user && auth()->id() !== $post->user_id)
                    @if(auth()->user()->isFollowing($post->user))
                        <form action="/unfollow/{{ $post->user->id }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="text-[10px] text-red-400 hover:text-red-300 uppercase font-bold tracking-wider border border-red-500/30 px-2 py-0.5 rounded hover:bg-red-500/10 transition">
                                [ Unfollow ]
                            </button>
                        </form>
                    @else
                        <form action="/follow/{{ $post->user->id }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="text-[10px] text-emerald-400 hover:text-emerald-300 uppercase font-bold tracking-wider border border-emerald-500/30 px-2 py-0.5 rounded hover:bg-emerald-500/10 transition">
                                [ Follow ]
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            @if(auth()->id() === $post->user_id)
                <form action="/post/{{ $post->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-gray-600 hover:text-red-500 transition text-[10px] uppercase font-bold">
                        [ DELETE ]
                    </button>
                </form>
            @endif
        </div>

        <p class="text-sm text-gray-200 leading-relaxed">
            {{ $post->content }}
        </p>

        <span class="text-[10px] text-gray-600">
            {{ $post->created_at->diffForHumans() }}
        </span>
    </div>
@endforeach
    @endauth
    <!-- GUEST PANEL -->
    @guest
        <div class="glass z-10 p-10 rounded-2xl w-[450px] text-center shadow-2xl relative">
            <h2 class="text-xl text-white tracking-tight mb-2">Access Restricted FOR OUAL FAM</h2>
            <p class="text-xs text-gray-500 mb-8">Authentication credentials required To Access The Fam Oual.</p>
            <div class="flex flex-col space-y-3">
                <a href="/login" class="w-full py-3 bg-white text-black text-xs font-bold uppercase tracking-widest rounded hover:bg-gray-200 transition">Initialize Login</a>
                <a href="/register" class="w-full py-3 border border-white/10 text-gray-400 text-xs font-bold uppercase tracking-widest rounded hover:text-white hover:border-white/40 transition">Create Identity</a>
            </div>
        </div>
    @endguest

</body>
</html>
