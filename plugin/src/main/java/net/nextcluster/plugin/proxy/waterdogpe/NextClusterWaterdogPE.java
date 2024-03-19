/*
 * MIT License
 *
 * Copyright (c) 2024 nextCluster
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

package net.nextcluster.plugin.proxy.waterdogpe;


import dev.waterdog.waterdogpe.ProxyServer;
import dev.waterdog.waterdogpe.event.defaults.PlayerDisconnectedEvent;
import dev.waterdog.waterdogpe.event.defaults.PlayerLoginEvent;
import dev.waterdog.waterdogpe.plugin.Plugin;
import net.nextcluster.plugin.proxy.waterdogpe.handler.ServerJoinHandler;

public final class NextClusterWaterdogPE extends Plugin {

    private WaterdogPEProxy proxy;

    @Override
    public void onEnable() {
        ProxyServer.getInstance().getConfiguration().getServerList().initEmpty();
        ProxyServer.getInstance().getServers().clear();

        this.proxy = new WaterdogPEProxy();
        ProxyServer.getInstance().getEventManager().subscribe(PlayerLoginEvent.class, this::handleLogin);
        ProxyServer.getInstance().getEventManager().subscribe(PlayerDisconnectedEvent.class, this::handleDisconnect);
        this.proxy.watch();

        ProxyServer.getInstance().setJoinHandler(new ServerJoinHandler(proxy));
    }


    public void handleLogin(PlayerLoginEvent event) {
        if (ProxyServer.getInstance().getServers().isEmpty()) {
            event.setCancelReason("No fallback server available");
            event.setCancelled(true);
        }
    }

    public void handleDisconnect(PlayerDisconnectedEvent event) {
        proxy.findFallback().ifPresent(serverInfo -> {
            event.getPlayer().sendMessage(event.getReason());
            event.setCancelled(true);
        });
    }
}
