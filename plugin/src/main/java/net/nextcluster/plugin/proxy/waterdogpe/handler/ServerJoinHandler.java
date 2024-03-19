package net.nextcluster.plugin.proxy.waterdogpe.handler;

import dev.waterdog.waterdogpe.network.connection.handler.IJoinHandler;
import dev.waterdog.waterdogpe.network.serverinfo.ServerInfo;
import dev.waterdog.waterdogpe.player.ProxiedPlayer;
import net.nextcluster.plugin.proxy.waterdogpe.WaterdogPEProxy;

public class ServerJoinHandler implements IJoinHandler {

    private final WaterdogPEProxy waterdogPEProxy;
    public ServerJoinHandler(WaterdogPEProxy waterdogPEProxy) {
        this.waterdogPEProxy = waterdogPEProxy;
    }

    @Override
    public ServerInfo determineServer(ProxiedPlayer proxiedPlayer) {
        final var server = waterdogPEProxy.findFallback();
        server.ifPresentOrElse(proxiedPlayer::connect, ()-> {
            proxiedPlayer.disconnect("No fallback server available");
        });

        return server.get();
    }
}
